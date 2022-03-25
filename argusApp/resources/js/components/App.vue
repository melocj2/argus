<template>
    <div class="wrapper">
        <nav id="sidebar" :class="{'closed': !showSidebar}">
            <div class="img"></div>
            <div class="sidebar-header">
                <h1>Vue Dashboard</h1>
            </div>
            <div class="links">
                <router-link to="/" class="btn btn-block btn-light">
                    Dashboard
                </router-link>
                <router-link to="/profile" class="btn btn-block btn-light">
                    Profile
                </router-link>
            </div>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light d-block">
                <button type="button" class="btn btn-dark btn-sm" @click="showSidebar = !showSidebar">
                    <span v-if="showSidebar" class="fa fa-times"></span>
                    <span v-else class="fa fa-bars"></span>
                </button>
                <router-link to="/profile" class="h4 d-block float-right text-dark">
                    <span class="fa fa-user"></span>
                </router-link>
            </nav>
            <div class="pt-3 pb-3">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'app',

        props: {
            user: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                showSidebar: true,
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

        // computed: {
        //     values() {
        //     let sensorData = firebase.database().ref('sensors');

        //     let store = this.hitCount;

        //     // let sensorVals = [];

        //     sensorData.once('value', function(snapshot) {

        //         // sensoreVals;

        //         let sensorSnap = {}

        //         //gas
        //         sensorSnap.gas = Math.round(snapshot.val().gas.value);

        //         //light
        //         sensorSnap.light = Math.round(snapshot.val().light.value);

        //         //temp
        //         let val =  snapshot.val().temp.value;
        //         let tempF = val.replace(/\D/g, '');
        //         let tempC = Math.round(tempF * 9 / 5 + 32);
        //         sensorSnap.temp = tempC;

        //         //moisture
        //         sensorSnap.moisture = Math.round(snapshot.val().moisture.value);
        //             console.log(sensorSnap)

        //         // sensorVals.push(sensorSnap);

        //         return sensorSnap

        //     });
        //     // console.log('check', sensorVals)
        //     // return sensorVals;
        //     }

        // },

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
.wrapper {
    display: flex;
    width: 100%;
    align-items: stretch;
}

#content {
    background: #f8f9fa;
    padding:15px;

    .navbar {
        padding:0 !important;
    }
}

#sidebar {
    width: 250px;
    min-height: 100vh;
    transition: all 0.3s;
    position:relative;
    padding:15px;
    color:white;

    &:after {
        content: "";
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background: rgba(27,27,27,.67);
        z-index: -1;
    }

    .img {
        content: '';
        position:absolute;
        top:0;
        left:0;
        z-index:-1;
        width:100%;
        height:100%;
        background:url('/images/nav-bg.jpg');
        background-size:cover;
        background-position:center;
        background-repeat:no-repeat;
    }

    h1 {
        font-size: 1.2rem;
        margin-bottom: 20px;
        border-bottom: 1px solid;
        padding-bottom: 12px;
    }

    .links {
        background:transparent;
        padding:5px 0;

        .router-link-exact-active {
            background:#007bff;
            border-color:#007bff;
            color:white;
        }
    }

    &.closed {
        margin-left: -250px;
    }
}

#content {
    width: 100%;
}
</style>
