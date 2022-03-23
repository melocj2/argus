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
    },
    mutations: {
        user (state, myCustomData) {
            state.user = myCustomData;
        }
    }
});

new Vue({
    el: '#app',
    router,
    store,
    components: {App}
});
