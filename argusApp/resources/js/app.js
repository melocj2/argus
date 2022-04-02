require('./bootstrap');
import Vue from 'vue';
import Vuex from 'vuex';

import App from './components/App.vue';
import router from './router';

var config = {
    apiKey: "AIzaSyB1RlWE4vCmCN8HHThtccQ6igKQZo5bWtM",
    authDomain: "pi-hms.firebaseapp.com",
    databaseURL: "https://pi-hms.firebaseio.com",
    projectId: "pi-hms",
    storageBucket: "pi-hms.appspot.com",
    messagingSenderId: "737726017750"
  };

  firebase.initializeApp(config);

  console.log(firebase);

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        user: null,
        sensorValues: {
            increment: 0,
            light: [],
            moisture: [],
            gas: [],
            temp: [],
        },
        dayTrend: [],
        monthTrend: [],
        sensorValues: {
            increment: 0,
            light: [],
            moisture: [],
            gas: [],
            temp: [],
        },
        currentReading: {
            light: 'reading data..',
            moisture: 'reading data..',
            gas: 'reading data..',
            temp: 'reading data..',
        },
        userPlants: [{
            userId: 'loading data...',
            name: 'loading data...',
            id: 'loading data...',
            image: 'loading data...',
            type: 'loading data...',
            age: 'loading data...',
            location: 'loading data...'
        }],
        userMessages: [{
            userId: 'loading data...',
            id: 'loading data...',
            message: 'loading data...',
            generatedAt: 'loading data...',
            type: 'loading data...',
        }],
    },
    mutations: {
        user (state, myCustomData) {
            state.user = myCustomData;
        },
        sensorValues (state, myCustomData) {
            state.sensorValues.increment = state.sensorValues.increment + 1;
            state.sensorValues.light.push(myCustomData.light);
            state.sensorValues.moisture.push(myCustomData.moisture);
            state.sensorValues.gas.push(myCustomData.gas);
            state.sensorValues.temp.push(myCustomData.temp);

            state.currentReading = myCustomData;

        },
        sensorValueReset (state) {
            state.sensorValues.increment = 0;
            state.sensorValues.light = [];
            state.sensorValues.moisture = [];
            state.sensorValues.gas = [];
            state.sensorValues.temp = [];
        },
        dayTrend (state, myCustomData) {
            state.dayTrend = myCustomData;
        },
        monthTrend (state, myCustomData) {
            state.monthTrend = myCustomData;
        },
        userPlants (state, myCustomData) {
            state.userPlants = myCustomData;
        },
        userMessages (state, myCustomData) {
            state.userMessages = myCustomData;
        }
    }
});

new Vue({
    el: '#app',
    router,
    store,
    components: {App}
});
