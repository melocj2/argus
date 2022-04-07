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
    //     tips: [
    //         {id: 1, tip: 'Prevent overwatering by touching the soil. If it feels damp, it does not need to be watered right away.'},
    //     {id: 2, tip: 'If some leaves on your plant seem dry or discolored, consider giving a bit more water than usual.'},
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 2;
    //         $tips->protip = 'If some leaves on your plant seem dry or discolored, consider giving a bit more water than usual.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 3;
    //         $tips->protip = 'To find out if it is time to start repotting your plants, always check their roots first.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 4;
    //         $tips->protip = 'Positioning is everything when it comes to indoor plants! Find a spot with the right amount of light.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 5;
    //         $tips->protip = 'Make sure to prune your plants and get rid of old-growth. This will help your plants grow again!';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 6;
    //         $tips->protip = 'When potting plants for the first time, make sure the soil is moist so the roots can begin working.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 7;
    //         $tips->protip = 'Ensure the roots of your plant are receiving enough air by placing them in pots with drainage holes.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 8;
    //         $tips->protip = 'Dust your plants! If too much dust collects on the leaves, they may not get enough sunlight.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 9;
    //         $tips->protip = 'Keep in mind that for many plants, higher humidity levels are crucial in helping them thrive.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 10;
    //         $tips->protip = 'Try and keep temperatures stable as major changes to the environment can stress plants out.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 11;
    //         $tips->protip = 'When repotting, consider choosing a planter that is only 1 - 3 inches larger than its current container.';
    //         $tips->save();

    //         $tips = new Tips();
    //         $tips->id = 12;
    //         $tips->protip = 'Fertilizer is always helpful, but using too much of it can do your plant more harm than good!';
    //         $tips->save();}]
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
