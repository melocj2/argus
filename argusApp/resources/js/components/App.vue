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


        methods: {
            storeAverageValues() {
                //average store sensor data

                let sensorObj = this.$store.state.sensorValues;

                let moisture = this.getAvg(sensorObj.moisture);
                let light = this.getAvg(sensorObj.light);
                let temp = this.getAvg(sensorObj.temp);
                let gas = this.getAvg(sensorObj.gas);

                //save averages to the database (will require a controller, model, migration, etc...)

                const formData = new FormData();
                formData.append('_method', 'POST');
                formData.append('moisture', moisture);
                formData.append('light', light);
                formData.append('temp', temp);
                formData.append('gas', gas);
                formData.append('user_id', this.user.id);
                // formData.append('plant_id', 1); leave this out for now, ask marco what approach is best for generating different data for different plants
                //method 1 -> can just use a transformer to change the data when it is being retrieved based on plant id provided
                //method

                 axios.post('/api/sensors', formData)
                    .then(response => {
                        return response.data;
                    }).then(data => {
                        this.$store.commit('currentReading', data);
                    }).catch(error => {
                        console.log(error);
                    });

                //display current reading and history on screen;

                //reset sensorValues
                this.$store.commit('sensorValueReset')
            },

            getAvg(arr) {
                let total = arr.reduce((a, b) => a + b, 0);
                let average = (total / arr.length) || 0;
                return Math.round(average);
            }
        },


        mounted() {

            this.$store.commit('user', this.user);

            let t = this;

            console.log("IIFE fired!");

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
                let tempC = Math.round(tempF * 9 / 5 + 32);
                sensorSnap.temp = tempC;

                //moisture
                sensorSnap.moisture = Math.round(snapshot.val().moisture.value);

                t.$store.commit('sensorValues', sensorSnap);

                console.log(t.$store.state.sensorValues)
            });


        }

    }

</script>

<style lang="scss" scoped>

</style>
