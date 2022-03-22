var config = {
    apiKey: "AIzaSyB1RlWE4vCmCN8HHThtccQ6igKQZo5bWtM",
    authDomain: "pi-hms.firebaseapp.com",
    databaseURL: "https://pi-hms.firebaseio.com",
    projectId: "pi-hms",
    storageBucket: "pi-hms.appspot.com",
    messagingSenderId: "737726017750"
};

    firebase.initializeApp(config);

(()=> {
    "use strict";
    console.log("IIFE fired!");

    let sensorData = firebase.database().ref('sensors');

    sensorData.on('value', function(snapshot){
        console.log(Math.round(snapshot.val().light.value));
    });

    sensorData.on('value', function(snapshot){
        console.log(snapshot.val().temp.value);
    });

    sensorData.on('value', function(snapshot){
        console.log(Math.round(snapshot.val().moisture.value));
    });

})();