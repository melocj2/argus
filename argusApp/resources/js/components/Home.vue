<template>
    <div>
        <h2 class="invisibleHeader">Home Page</h2>

         <ul class="messageList">
            <li v-for="message in userMessages" :key="message.id">
                <p>message: {{message.message}}</p>
                <p>alert generated at: {{message.generatedAt}}</p>

            </li>
        </ul>

        <AddPlantModal v-if="addPlantOpen" @closeAddPlant="toggleAddPlant" />

        <div>
            <p>{{weather.current.temp_c}}°C</p>
            <p>{{weather.location.name}}</p>
            <p>{{weather.location.localtime}}</p>
        </div>


        <div class="searchBar">
            <input placeholder="search plants" v-model="myPlantFilter" class="myPlantFilter"><span class="font-awesome-icons search"></span>
        </div>
        <a class="addNewPlant" @click="toggleAddPlant"><span class="font-awesome-icons add"></span> add</a><br/><br/><br/>
        <ul class="plantList">
            <li v-for="plant in userPlants" :key="plant.id">
                <div class="plantSideImage" :style="{'background-image': `url(${plant.image})`}"></div>
                <p class="plantName"><span class="font-awesome-icons plant"></span> {{plant.name}}</p>
                <p>location: {{plant.location}}</p>
                <p>status: {{isHealthy}}</p>
                <router-link class="routerLink" :to="{ name: 'plant', params: { id: `${plant.id}`}}">view plant</router-link>
            </li>
        </ul>
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
                },
                myPlantFilter: '',
            }
        },

        computed: {
            //NOTE WHEN ADDING A USER PLANT YOU MUST UPDATE BOTH STORE AND DB!!!!
            userPlants() {

                let plants = this.$store.state.userPlants.filter(item => {
                    return item.name.includes(this.myPlantFilter);
                })
                return plants;
            },

            isHealthy() {
                let msgs = this.userMessages;

                let health = 'healthy';

                msgs.forEach(item => {
                    if (item.message != 'loading data...' || item.message != null) {
                        health = 'needs attention';
                    }
                })

                return health;
            },

            userMessages() {

                let messages = this.$store.state.userMessages.filter(item => {
                    return item.message;
                })
                console.log("SEE HOME MESSAGES HERE", messages)
                return messages;
            }

            // messages() {
            //     //check values of 'current readings'

            //     //if any values are high or low, generate a message and tie it to a post request to messages
            //     //NOTE: get all plants

            //     //send the message to update the db, and then receive an array back of all messages associated with the user

            //     //
            // }
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
<style lang="scss">
@import "../.././sass/variables/breakpoints.scss";
@import "../.././sass/variables/fonts.scss";
@import "../.././sass/variables/colors.scss";
@import "../.././sass/mixins/all-mixins.scss";

.searchBar {
    position: relative;
    .search::before {
        @include awesomeIcon($grey, 'search');
        position: absolute;
        left: 15px;
        top: 1em;
    }
    .myPlantFilter {
        text-decoration: none;
        background: $white;
        padding: 15px 15px 15px 40px;
        border-radius: 10px;
        box-sizing: border-box;
        width: 20em;
        font-family: $font;
    }
}

.addNewPlant {
    cursor: pointer;
    .add::before {
         @include awesomeIcon($purple, 'add');
    }
}

.plantName {
    .plant::before {
         @include awesomeIcon($purple, 'plant');
    }
}

.plantSideImage {
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 15rem;
    width: 15rem;
    border-radius: 1rem;
}

</style>