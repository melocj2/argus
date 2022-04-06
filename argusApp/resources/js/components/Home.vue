<template>
    <div class="homePage">
        <h2 class="invisibleHeader">Home Page</h2>
        <div class="headGroup">
            <div class="homeLogo" :style="{'background-image': `url(/images/logoP.svg)`}"></div>
            <h2 class="greeting">hi, {{user.name}}.</h2>
            <h5 class="updateIntro">Here's what happened while you were away:</h5>
        </div>

         <div class="messageList">
            <div v-for="message in userMessages" :key="message.id" class="msgCard">
                <p class="msgContent">{{message.message}}
                    <span class="msgTimestamp">{{message.generatedAt}}</span>
                </p>
            </div>
        </div>

        <div class="weatherCard">
            <h2 class="weatherTemp">{{weather.current.temp_c}}°C</h2>
            <h2 class="weatherLocation">{{weather.location.name}}</h2>
            <h5 class="weatherDateTime">{{weather.location.localtime}}</h5>
        </div>

        <AddPlantModal v-if="addPlantOpen" @closeAddPlant="toggleAddPlant" />
        <div class="searchAdd">
            <div class="searchBar">
                <input placeholder="search plants" v-model="myPlantFilter" class="myPlantFilter"><span class="font-awesome-icons search"></span>
            </div>
            <a class="addNewPlant" @click="toggleAddPlant"><span class="font-awesome-icons add"></span> add new plant</a>
        </div>

        <div class="plantList">
            <div class="plantListGrouping" v-for="plant in userPlants" :key="plant.id">
                <div class="plantSideImage" :style="{'background-image': `url(${plant.image})`}"></div>
                <h4 class="plantNameHome"><span class="font-awesome-icons plant"></span> {{plant.name}}</h4>
                <h5 class="plantLocationHome">location: {{plant.location}}</h5>
                <h5 class="plantStatusHome">status: {{isHealthy}}</h5>
                <router-link class="routerLink view viewPlantHome" :to="{ name: 'plant', params: { id: `${plant.id}`}}">view plant ></router-link>
            </div>
        </div>
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

                let prodArr = [];

                let messages = this.$store.state.userMessages.forEach(item => {
                    if (item.message) {
                        prodArr.push(item);
                    }
                })

                return prodArr;
            },

            user() {
                return this.$store.state.user;
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
                if (document.querySelector("body").style.overflow != 'hidden') {
                    document.querySelector("body").style.overflow = 'hidden';

                } else {
                    document.querySelector("body").style.overflow = 'auto';
                }
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

.homePage {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 90%;
    margin: 0 auto;
    .headGroup {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 80%;
        .homeLogo {
            background-position: center;
            background-repeat: no-repeat;
            background-size: fit;
            height: 8rem;
            width: 15rem;
            border-radius: 1rem;
            background-color: transparent;
        }
        .greeting {
            color:$purple;
            font-weight: bold;
            margin-bottom: 1em;
        }
        .updateIntro {
            font-weight: normal;
            width: 80%;
            text-align: center;
            margin-bottom: 3em;
        }
    }
    .messageList {
         width: 80%;
         margin-bottom: 2em;
        .msgCard {
            background: $greyGreen;
            padding: 0.8em 1.5rem;
            border-radius: 1em;
            margin-bottom: 1em;
            .msgContent {
                color: black;
                margin-top: 1em;
                margin-bottom: 0.5em;
            }
            .msgTimestamp {
                color: $grey-2;
                display: block;
                text-align: right;
                margin-top: 0.5em;
            }
        }
    }
    .weatherCard {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 80%;
        box-sizing: border-box;
        margin-bottom: 3em;
        background: $white;
        padding: 2em;
        @include border;
        border-radius: 2em;
        text-align: center;
        .weatherTemp {
            font-size: 4rem;
            color: $green;
            margin: 0.25em 0;
        }
        .weatherLocation {
            color: $green;
            margin-bottom: 1em;
        }
        .weatherDateTime {
            color: $green;
            opacity: 0.8;
        }
    }
    .searchAdd {
        width: 80%;
        box-sizing: border-box;
        margin-bottom: 2em;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        .searchBar {
            position: relative;
            width: 100%;
            .search::before {
                @include awesomeIcon($grey, 'search');
                position: absolute;
                left: 15px;
                top: 1em;
            }
            .myPlantFilter {
                width: 100%;
                text-decoration: none;
                background: $white;
                padding: 15px 15px 15px 40px;
                border-radius: 12px;
                box-sizing: border-box;
                font-family: $font;
                font-size: $card-mobile;
            }
        }
        .addNewPlant {
            font-size: $card-mobile;
            width: 100%;
            text-align: center;
            margin-top: 1em;
            color: $purple;
            cursor: pointer;
            .add::before {
                @include awesomeIcon($purple, 'add');
            }
        }
    }
    .plantList {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 80%;
        .plantListGrouping {
            display: grid;
            grid-template-rows: repeat(11, 1fr);
            grid-template-columns: repeat(8, 1fr);
            height: 35em;
            width: 100%;
            background-color: $green;
            border-radius: 20px;
            @include border;
            margin-bottom: 1em;
            text-align: center;
            .plantSideImage {
                grid-row: 1/8;
                grid-column: 1/9;
                width: 100%;
                height: 100%;
                color: $white;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                border-radius: 20px 20px 0 0;
            }
            .plantNameHome {
                grid-row: 7/9;
                grid-column: 2/8;
                justify-self: center;
                align-self: center;
                background: $white;
                border-radius: 0.5em;
                padding: 0.5em 1em;
                color: $purple;
                .plant::before {
                    @include awesomeIcon($purple, 'plant');
                }
            }
            .plantLocationHome {
                grid-row: 8/10;
                grid-column: 2/8;
                justify-self: center;
                align-self: center;
                color: $white;
            }
            .plantStatusHome {
                grid-row: 9/11;
                grid-column: 2/8;
                justify-self: center;
                align-self: center;
                color: $white;
            }
            .viewPlantHome {
                grid-row: 11/12;
                grid-column: 1/9;
                background: $white;
                width: 100%;
                height: 100%;
                color: $purple;
                border-radius: 0 0 19px 19px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                font-size: $card-mobile;
                font-weight: bold;
            }
        }
    }
}

@media (min-width: $tablet-break) {
    .homePage {
        display: grid;
        grid-template-columns: repeat(13, 1fr);
        grid-template-rows: repeat(5, 1fr) repeat(1, 10px);
        width: 100%;
        padding-top: 5em;
        padding-bottom: 10em;
        min-height: 0;
        min-width: 0;
        .headGroup {
            width: 100%;
            grid-row: 1/3;
            grid-column: 2/8;
            align-self: center;
            justify-self: center;
            .homeLogo {
                background-position: center;
                background-repeat: no-repeat;
                background-size: contain;
                width: 10em;
            }
            .greeting {
                font-weight: bold;
                margin-bottom: 0.5em;
            }
            .updateIntro {
                width: 100%;
                margin-bottom: 1em;
            }

        }
        .messageList {
            width: 100%;
            height: 15em;
            grid-row: 3/5;
            grid-column: 2/8;
            @include scrollbar($grey, $bg);
            align-self: center;
            justify-self: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 0;
            .msgCard {
                width: 100%;
                border-radius: 10px;
                box-sizing: border-box;
            }
        }
        .weatherCard {
            width: 100%;
            height: 90%;
            grid-row: 1/5;
            grid-column: 9/13;
            align-self: flex-start;
            justify-self: center;
            display: flex;
            direction: column;
            align-items: space-between;
            margin-bottom: 0;
            .weatherTemp {
                font-size: 5rem;
            }
        }
        .searchAdd {
            width: 100%;
            grid-row: 5/6;
            grid-column: 2/13;
            align-self: center;
            justify-self: center;
        }
        .plantList {
            width: 100%;
            grid-row: 6/8;
            grid-column: 2/13;
            align-self: center;
            justify-self: center;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 5%;
            .plantListGrouping {
                height: 35em;
                width: 47%;
            }
        }
    }
}

@media (min-width: $desktop-break) {
    .homePage {
        height: 70em;
        padding-top: 2em;
        box-sizing: border-box;
        display: grid;
        grid-template-rows: repeat(16, 1fr);
        grid-template-columns: repeat(17, 1fr);
        .headGroup {
            grid-column: 2/9;
            grid-row: 2/7;
            width: 100%;
            align-self: center;
            justify-self: center;
            .homeLogo {
                width: 13em;
            }
            .greeting {
                font-weight: bold;
                margin-bottom: 0.5em;
            }
            .updateIntro {
                width: 100%;
                margin-bottom: 1em;
            }
        }
        .messageList {
            grid-column: 2/9;
            grid-row: 7/11;
            width: 100%;
            align-self: center;
            justify-self: center;
            margin-bottom: 0.5em;
            align-items: center;
        }
        .weatherCard {
            height: auto;
            grid-column: 2/9;
            grid-row: 11/16;
            width: 100%;
            display: initial;
            .weatherTemp {
                float: right;
                font-size: 3em;
            }
            .weatherLocation {
                float: left;
                margin-bottom: 0;
            }
            .weatherDateTime {
                float: left;
                width: 100%;
                text-align: left;
            }
        }
        .searchAdd {
            grid-column: 10/17;
            grid-row: 4/6;
            width: 100%;
            align-self: end;
            justify-self: center;
        }
        .plantList {
            height: 30em;
            grid-column: 10/17;
            grid-row: 6/16;
            display: block;
            align-self: flex-start;
            justify-self: center;
            @include scrollbar($grey, $bg);
            .plantListGrouping {
                height: 14em;
                width: 99%;
                display: grid;
                grid-template-columns: repeat(8, 1fr);
                grid-template-rows: repeat(5, 1fr);
                background-color: $green;
                border-radius: 30px;
                @include border;
                margin-bottom: 1em;
                .plantSideImage {
                    grid-row: 1/6;
                    grid-column: 1/4;
                    width: 100%;
                    height: 100%;
                    color: $white;
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: cover;
                    border-radius: 20px 0 0 20px;
                    box-sizing: border-box;
                }
                .plantNameHome {
                    grid-row: 1/3;
                    grid-column: 4/9;
                    justify-self: center;
                    align-self: center;
                    color: $white;
                    background: none;
                    .plant::before {
                        @include awesomeIcon($white, 'plant');
                    }
                }
                .plantLocationHome {
                    grid-row: 2/4;
                    grid-column: 4/9;
                    justify-self: center;
                    align-self: center;
                    color: $white;
                }
                .plantStatusHome {
                    grid-row: 3/5;
                    grid-column: 4/9;
                    justify-self: center;
                    align-self: center;
                    color: $white;
                }
                .viewPlantHome {
                    grid-row: 5/6;
                    grid-column: 4/9;
                    justify-self: end;
                    align-self: end;
                    background: $white;
                    width: 100%;
                    height: 100%;
                    color: $purple;
                    border-radius: 0 0 29px 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    font-size: $card-mobile;
                    font-weight: bold;
                }
            }
        }
    }
}

</style>