<template>
    <div class="plantInfoBlanket">
        <h2 class="invisibleHeader">More Plant Info Modal</h2>
        <div class="plantInfoDetail" v-if="addedPlantInfo">
            <a class="closePlantInfoModal" @click="closePlantInfo">
                <span class="fa-stack faWrapper">
                    <i class="fa circle fa-stack-2x"></i>
                    <i class="fa close fa-stack-1x"></i>
                </span>
            </a>
            <div class="plantProfileEditImage" :style="{'background-image': `url(${currentPlant.image})`}"></div>
            <div class="textInfoWrap">
                <div class="scientificName">
                    <h5>scientific name:</h5>
                    <p>{{this.addedPlantInfo.species}}</p>
                </div>
                <div class="plantAgeInfo">
                    <h5>age:</h5>
                    <p>{{this.plant.age}}</p>
                </div>
                <div class="plantDescriptionInfo">
                    <h5>description:</h5>
                    <p>{{this.addedPlantInfo.info}}</p>
                </div>
                <div class="proTip">
                    <h5>pro tip!</h5>
                    <p>{{tips}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    props: ["currentPlant"],

    data() {
        return {
            plant: this.currentPlant,
            addedPlantInfo: false,
            tips: {}
        }
    },

    methods: {
        closePlantInfo() {
            return this.$emit('closePlantInfo');
        },

        // findPlant() {}
    },

    mounted() {

            let check = this.plant.type.toLowerCase().split(' ');


            if (check[check.length-1] != 'plant') {
                check.push('plant')
            }

            let query = check.join(' ');

            let t = this;
            fetch(`https://api.gbif.org/v1/species/search?q=${query}`)
            .then(response => {
                return response.json();
            })
            .then(data => {

                let result = {};

                if (data.results.length > 0 && data.results[0].descriptions && data.results[0].descriptions.length > 0 && data.results[0].species) {


                    result.species = data.results[0].species;
                    result.info = data.results[0].descriptions[0].description;

                } else {
                    // t.secondCheck(query); call with plant added at the end

                    result.species = 'not found';
                    result.info = 'not found, please ensure the plant type is spelled correctly';

                }

                this.addedPlantInfo = result;
                console.log("API CALL", data);
            })
            .catch(error => {
                console.log(error);
		    });
        },

        created: function () {
            let vm = this;
            fetch(`http://localhost:8082/api/get-tips`)
            .then(function(response){
                return response.json();
            })
            .then(function(data) {
            vm.tips = data[Math.floor(Math.random()*12)].protip;
            console.log(data[Math.floor(Math.random()*12)].protip);
		    });
        },


}
</script>

<style lang="scss">
    @import "../../../.././sass/variables/breakpoints.scss";
    @import "../../../.././sass/variables/fonts.scss";
    @import "../../../.././sass/variables/colors.scss";
    @import "../../../.././sass/mixins/all-mixins.scss";

    .plantInfoBlanket {
        overflow: hidden;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
        background-color: rgba(161, 161, 161, 0.8);;
        z-index: 600;
        .plantInfoDetail {
            position: fixed;
            top: 1em;
            width: 20em;
            height: 35em;
            @include scrollbar($grey, $bg);
            left: 50%;
            transform: translateX(-50%);
            background-color: $bg;
            border-radius: 20px;
            @include border;
            padding: 0;
            z-index: 1000;
            display: grid;
            grid-template-rows: repeat(8, 1fr);
            grid-template-columns: repeat(2, 1fr);
            .plantProfileEditImage {
                grid-row: 1/5;
                grid-column: 1/3;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                width: 100%;
                height: 100%;
                border-radius: 10px 10px 0 0;
            }
            .textInfoWrap {
                grid-row: 5/9;
                grid-column: 1/3;
                display: flex;
                flex-direction: column;
                justify-content: space-around;
                justify-self: center;
                text-align: center;
                align-self: center;
                height: 80%;
                width: 90%;
                h5 {
                    color: $green;
                    font-weight: bold;
                }
                p {
                    color: $purple;
                }
                .label {
                    color: $green;
                    font-weight: bold;
                }
            }
            .closePlantInfoModal {
                position: absolute;
                top: 0.5em;
                right: 0.5em;
                cursor: pointer;
                .faWrapper {
                    font-size: $card-tablet;
                    .circle::before {
                        @include awesomeIcon($white, 'circle');
                    }
                    .close::before {
                        @include awesomeIcon($purple, 'close');
                    }
                }
            }
        }
    }
    @media (min-width: $tablet-break) {
        .plantInfoBlanket {
            .plantInfoDetail {
                width: 30em;
                top: 5rem;
                height: 45em;
                @include scrollbar($grey, $bg);
                left: 50%;
                transform: translateX(-50%);
                .textInfoWrap {
                    grid-row: 5/9;
                    grid-column: 1/3;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-around;
                    justify-self: center;
                    text-align: center;
                    align-self: center;
                    height: 80%;
                    width: 80%;
                    h5 {
                        color: $green;
                        font-weight: bold;
                    }
                    p {
                        color: $purple;
                    }
                }
            }
        }
    }
    @media (min-width: $desktop-break) {
        .plantInfoBlanket {
            .plantInfoDetail {
                width: 65em;
                top: 5rem;
                height: 45em;
                @include scrollbar($grey, $bg);
                left: 50%;
                transform: translateX(-50%);
                grid-template-rows: repeat(8, 1fr);
                grid-template-columns: repeat(6, 1fr);
                .plantProfileEditImage {
                    grid-row: 1/9;
                    grid-column: 1/4;
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: cover;
                    width: 100%;
                    height: 100%;
                    border-radius: 10px 0 0 10px;
                }
                .textInfoWrap {
                    grid-row: 1/9;
                    grid-column: 4/9;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-around;
                    justify-self: center;
                    text-align: center;
                    align-self: center;
                    height: 80%;
                    width: 80%;
                    h5 {
                        color: $green;
                        font-weight: bold;
                    }
                    p {
                        color: $purple;
                    }
                }
            }
        }
    }
</style>
