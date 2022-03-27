<template>
    <div>
        <h1>HOME</h1>

        <AddPlantModal v-if="addPlantOpen" @closeAddPlant="toggleAddPlant" />
        <button @click="toggleAddPlant">Add Plant</button><br/><br/><br/>

        <div>
            <p>{{weather.current.temp_c}}°C</p>
            <p>{{weather.location.name}}</p>
            <p>{{weather.location.localtime}}</p>
        </div>

        <router-link to="/plant">
            view plant 1
        </router-link>
    </div>
</template>

<script>
    import AddPlantModal from './partials/home/AddPlantModal.vue';

    export default {

        data() {
            return {
                addPlantOpen: false,
                weather: {
                    current: {},
                    location: {}
                }
            }
        },

        created: function () {
            let vm = this;
            fetch(`http://api.weatherapi.com/v1/current.json?key=0012db7a6ba941cbb04194526222603&q=42.9849,-81.2453&aqi=no`)
            .then(function(response){
                return response.json();
            })
            .then(function(data) { 
            vm.weather = data;
            console.log(data);
		    });        
        },

        components: {
            AddPlantModal
        },

        methods: {
            toggleAddPlant() {
                this.addPlantOpen = !this.addPlantOpen;
            }
        }

    };
</script>
