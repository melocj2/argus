<template>
    <div>

 <Nav />
        <router-view />


    </div>
</template>

<script>

    import Nav from './partials/nav/Nav.vue';

    export default {

        components: {
            Nav
        },

        name: 'app',

        props: {
            user: {
                type: Object,
                required: true
            }
        },

        computed: {


        },


        methods: {
            generateAlerts() {

                let messages = this.getMessagesFromReadings();

                let userMessages = this.$store.state.userMessages;

                let userMsgObj = {};

                userMessages.forEach(item => {
                    userMsgObj[item.type] = {message: item.message, id: item.id};
                })


                if (messages.gas != userMsgObj.gas.message) {
                    //update the db and the store

                    this.updateAppUserMessages('gas', messages.gas, userMsgObj.gas.id);
                    // this.sendSMSAlert('gas', messages.gas);

                    //cascade check messages to determine if you should send an sms update or not
                }

                if (messages.light != userMsgObj.light.message) {
                    //update the db and the store

                    this.updateAppUserMessages('light', messages.light, userMsgObj.light.id);
                    // this.sendSMSAlert('light', messages.light);

                    //cascade check messages to determine if you should send an sms update or not
                }

                if (messages.moisture != userMsgObj.moisture.message) {
                    //update the db and the store

                    this.updateAppUserMessages('moisture', messages.moisture, userMsgObj.moisture.id);
                    // this.sendSMSAlert('moisture', messages.moisture);

                    //cascade check messages to determine if you should send an sms update or not
                }

                if (messages.temp != userMsgObj.temp.message) {
                    //update the db and the store

                    this.updateAppUserMessages('temp', messages.temp, userMsgObj.temp.id);
                    // this.sendSMSAlert('temperature', messages.temp);

                    //cascade check messages to determine if you should send an sms update or not
                }
            },

            sendSMSAlert(sensor, message) {

                console.log("handed to sms alerts BEFORE", sensor, message);

                if (message == null) {
                    message = `${sensor} has returned to optimal levels.`
                }

                console.log("handed to sms alerts AFTER", sensor, message);

                axios.post(`/api/messages/sendAlert`, {
                    message: message,
                    _method: 'POST'
                })
                .then(() => {
                    console.log('nice, front end handled')
                })
                .catch(error => {
                    console.log(error);
                });

            },

            pullSensorData() {

                let dayTrendArr = [];
                let monthTrendArr = [];

                 axios.get('/api/sensors')
                .then(response => {

                    response.data.forEach(item => {
                        if (item.type === 1) {
                            dayTrendArr.push(item);
                        } else if (item.type === 2) {
                            monthTrendArr.push(item);
                        };
                    })
                })
                .then(() => {
                    if (dayTrendArr.length > 0) {
                        this.$store.commit('dayTrend', dayTrendArr);
                    }
                    if (monthTrendArr.length > 0) {
                        this.$store.commit('monthTrend', monthTrendArr);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            },

            updateAppUserMessages (sensor, message, id) {

                axios.post(`/api/messages/${id}`, {
                    type: sensor,
                    message: message,
                    _method: 'PATCH'
                })
                .then(response => {
                    let userMessages = this.$store.state.userMessages;
                    let newMessages = [];
                    userMessages.forEach(item => {
                        if (item.id == response.data.id) {
                            item = response.data;
                        }
                        newMessages.push(item);
                    })
                    this.$store.commit('userMessages', newMessages);
                    return response.data;
                })
                .then(item => {
                    let name = item.type == 'temp' ? 'temperature' : item.type;
                    this.sendSMSAlert(name, item.message);
                })
                .catch(error => {
                    console.log(error);
                });

            },

            compressPastData() {

                const formData = new FormData();
                formData.append('_method', 'PATCH');

                axios.post('/api/sensors/compress', formData)
                    .then(response => {
                        console.log('check THIS HERE', response.data);
                    }).then(() => {
                        this.pullSensorData();
                    }).catch(error => {
                        console.log(error);
                    });
            },

            //generates an object with messages based on current sensor readings
            getMessagesFromReadings() {

                let readings = this.$store.state.currentReading;

                let messages = {};

                if (readings.gas > 50 && readings.gas <= 60) {
                    messages.gas = "warning: currently detecting high levels of carbon monoxide, please move plant.";
                } else if (readings.gas > 60 && readings.gas <= 90) {
                    messages.gas = "warning: currently detecting high levels of carbon monoxide and hydrogen gas, please move plant.";
                } else if (readings.gas > 90) {
                    messages.gas = "warning: currently detecting high levels of carbon monoxide, hydrogen gas, and alcohol, please move plant.";
                } else {
                    messages.gas = null;
                }

                if (readings.light > 86) {
                    messages.light = "plant is currently not getting enough light, please move plant to a better lit area.";
                } else if (readings.light < 5) {
                    messages.light = "plant is currently getting too much direct light, please move plant into a shadier area.";
                } else {
                    messages.light = null;
                }

                if (readings.moisture > 90) {
                    messages.moisture = "plant is currently too dry, please water.";
                } else if (readings.moisture < 50) {
                    messages.moisture = "plant is currently too damp, please drain.";
                } else {
                    messages.moisture = null;
                }

                 if (readings.temp > 35) {
                    messages.temp = "warning: plant is currently far too hot, please move to a cooler area.";
                } else if (readings.temp > 24) {
                    messages.temp = "plant is currently too hot, please move to a cooler area.";
                } else if (readings.temp < 5) {
                    messages.temp = "warning: plant is currently far too cold, please move to a warmer area.";
                } else if (readings.temp < 15) {
                    messages.temp = "plant is currently too cold, please move to a warmer area.";
                } else {
                    messages.temp = null;
                }

                return messages;
            },

            storeAverageValues() {
                //average store sensor data

                let sensorObj = this.$store.state.sensorValues;

                let moisture = this.getAvg(sensorObj.moisture);
                let light = this.getAvg(sensorObj.light);
                let temp = this.getAvg(sensorObj.temp);
                let gas = this.getAvg(sensorObj.gas);

                const formData = new FormData();
                formData.append('_method', 'POST');
                formData.append('moisture', moisture);
                formData.append('light', light);
                formData.append('temp', temp);
                formData.append('gas', gas);

                 axios.post('/api/sensors', formData)
                    .then(response => {
                        console.log(response.data);
                    }).catch(error => {
                        console.log(error);
                    });

                this.$store.commit('sensorValueReset');
                this.generateAlerts();
            },

            getUserPlantList() {
                 axios.get(`/api/plants/${this.user.id}`)
                .then(response => {
                    this.$store.commit('userPlants', response.data);
                })
                .catch(error => {
                    console.log(error);
                });
            },

            getUserMessageList() {
                 axios.get(`/api/messages/${this.user.id}`)
                .then(response => {
                    this.$store.commit('userMessages', response.data);
                })
                .catch(error => {
                    console.log(error);
                });
            },

            getAvg(arr) {
                let total = arr.reduce((a, b) => a + b, 0);
                let average = (total / arr.length) || 0;
                return Math.round(average);
            }
        },


        mounted() {

            this.$store.commit('user', this.user);

            this.compressPastData();

            this.getUserPlantList();

            this.getUserMessageList();

            let t = this;

            let sensorData = firebase.database().ref('sensors');

            sensorData.on('value', function(snapshot){

                let sensorSnap = {}

                if (t.$store.state.sensorValues.increment > 1) {
                    t.storeAverageValues();
                }

                //gas
                sensorSnap.gas = Math.round(snapshot.val().gas.value);

                //light
                sensorSnap.light = Math.round(snapshot.val().light.value);

                //temp
                let val =  snapshot.val().temp.value;
                let tempF = val.replace(/\D/g, '');
                let tempC = Math.round((tempF-32)/1.8);
                sensorSnap.temp = tempC;

                //moisture
                sensorSnap.moisture = Math.round(snapshot.val().moisture.value);

                //inside store, this also adjusts current readings as well, split to allow more easy to read access to the current readings,
                //instead of fetching the last item in the list of constantly changing arrays
                t.$store.commit('sensorValues', sensorSnap);


            });


        }

    }

</script>

<style lang="scss" scoped>



</style>
