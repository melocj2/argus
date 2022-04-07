<template>
    <div class="addPlantBlanket">
        <h2 class="invisibleHeader">Add Plant Modal</h2>

            <form ref="plantForm" class="addPlantModalForm" enctype="multipart/form-data">

            <h2 class="plantModalTitle">add a plant</h2>

            <a class="closeAddPlantModal" @click="closeAddPlant">
                <span class="fa-stack faWrapper">
                    <i class="fa circle fa-stack-2x"></i>
                    <i class="fa close fa-stack-1x"></i>
                </span>
            </a>
            <label for="image" class="addPlantImage">
                <span class="inAddImg">
                    <span class="fa-stack faWrapper">
                        <i class="fa circle fa-stack-2x"></i>
                        <i v-if="!fileLoaded" class="fa add fa-stack-1x"></i>
                        <i v-else class="fa check fa-stack-1x"></i>
                    </span>
                {{fileLoaded ? 'file uploaded!' : 'upload a picture of your plant'}}
                </span>
            </label>
            <input type="file" @change="updateFile" class="form-control" name="image" id="image" hidden/>
            <input type="hidden" name="upload_plant" value="1" />
            <input type="text" class="form-control" name="name" id="name" placeholder="name your plant"/>
            <input type="text" class="form-control" name="type" id="type" placeholder="plant type"/>
            <input type="text" class="form-control" name="location" id="location" placeholder="plant location (e.g. 'kitchen')"/>
            <label for="datePlanted" class="dateLabel">date planted:
                <input type="date" class="form-control" name="date_planted" id="datePlanted" :value="currentDate" min="2000-01-01" :max="currentDate">
            </label>

            <div v-if="errors" >

                    <p>{{errors}}</p>

            </div>
            <button class="addPlantButton" @click="addPlant" :disabled="disabledAdd">
                create plant
            </button>
          </form>

    </div>
</template>

<script>
    export default {

    data() {
        return {
            currentDate: new Date().toISOString().slice(0, 10),
            disabledAdd: null,
            errors: null,
            fileLoaded: false,
        }
    },

    methods: {

        closeAddPlant() {
            return this.$emit('closeAddPlant');
        },

        addPlant() {
            this.disabledAdd = true;
            this.errors = null;

            const PlantFormData = new FormData(this.$refs.plantForm);

            PlantFormData.append('_method', 'POST');

            PlantFormData.append('user_id', this.$store.state.user.id);

            axios.post(`/api/plants`, PlantFormData)

            .then(response => {
                return response.data;
            })
            .then(() => {
                return axios.get(`/api/plants/${this.$store.state.user.id}`);
            })
            .then(response => {
                // the second call is made to pull ALL plants fresh from the db (with updated plant) and commit them to the store
                this.$store.commit('userPlants', response.data);

            })
            .then(() => {
                this.closeAddPlant(); //you were here
            })
            .catch(error => {
                this.errors = 'the data you provided was invalid';
            })
            .then(() => {
                this.disabledAdd = false; //you were here
            })
            ;

        },

        updateFile() {
            this.fileLoaded = true;
        }


    }
}
</script>

<style lang="scss">

    @import "../../../.././sass/variables/breakpoints.scss";
    @import "../../../.././sass/variables/fonts.scss";
    @import "../../../.././sass/variables/colors.scss";
    @import "../../../.././sass/mixins/all-mixins.scss";

    .addPlantBlanket {
        overflow: hidden;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
        background-color: rgba(161, 161, 161, 0.8);;
        z-index: 600;
        .addPlantModalForm {
            position: fixed;
            top: 1em;
            height: 30em;
            width: 75%;
            left: 50%;
            transform: translateX(-50%);
            background-color: $bg;
            border-radius: 20px;
            @include border;
            padding: 4em 2em;
            z-index: 1000;
            display: flex;
            justify-content: space-around;
            flex-direction: column;
            gap: 1em;
            text-align: center;
            color: $purple;
            .closeAddPlantModal {
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
            .addPlantImage {
                height: 8em;
                width: 8em;
                border-radius: 20px;
                @include border;
                background: $white;
                align-self: center;
                justify-self: center;
                align-items: center;
                justify-content: center;
                padding: 2em;
                cursor: pointer;
                .inAddImg {
                    border: 2px dashed $grey;
                    width: 100%;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                    border-radius: 1em;
                    .faWrapper {
                        font-size: $card-mobile;
                        .circle::before {
                            @include awesomeIcon(transparent, 'circle');
                        }
                        .add::before {
                            @include awesomeIcon($purple, 'add');
                        }
                        .check::before {
                            @include awesomeIcon($purple, 'check');
                        }
                    }
                }
            }
            .addPlantButton {
                @include buttonStyle;
                @include border;
                width: 10em;
                align-self: center;
                font-size: $card-mobile;
                padding: 0.5em 1em;
                cursor: pointer;
            }
            .dateLabel {
                display: inline-block;
                width: 100%;
                text-align: center;

            }
        }
    }

    @media (min-width: $tablet-break) {
        .addPlantBlanket {
            .addPlantModalForm {
                height: 40em;
                min-width: 30em;
                top: 5%;
                transform: translateX(-50%);
                justify-content: space-around;
                .closeAddPlantModal {
                    font-size: $card-desktop;
                    .faWrapper {
                    }
                }
                .addPlantImage {
                    height: 20em;
                    width: 20em;
                    .inAddImg {
                        justify-content: space-around;
                        font-size: $card-desktop;
                        .faWrapper {
                            font-size: $head-mobile;
                        }
                    }
                }
            }
        }
    }

    @media (min-width: $desktop-break) {
        .addPlantBlanket {
            .addPlantModalForm {
                height: 35em;
                width: 70em;
                top: 5em;
                transform: translateX(-50%);
                display: grid;
                grid-template-rows: repeat(10, 1fr);
                grid-template-columns: repeat(17, 1fr);
                input {
                    font-size: $body-desktop;
                }
                .closeAddPlantModal {
                    top: 1.5em;
                    right: 1.5em;
                    .faWrapper {
                        font-size: $head-mobile;
                    }
                }
                .plantModalTitle{
                    grid-row: 1/3;
                    grid-column: 2/9;
                    width: 100%;
                    text-align: left;
                    justify-self: center;
                    align-self: center;
                }
                .addPlantImage {
                    grid-row: 4/9;
                    grid-column: 2/7;
                    width: 100%;
                    height: 100%;
                    justify-self: center;
                    align-self: center;
                    .inAddImg {
                        justify-content: center;
                        .faWrapper {
                            margin-bottom: 1em;
                            font-size: $head-desktop;
                            .circle::before {
                                @include awesomeIcon(transparent, 'circle');
                            }
                            .add::before {
                                @include awesomeIcon($purple, 'add');
                            }
                        }
                    }
                }
                .addPlantButton {
                    grid-row: 8/10;
                    grid-column: 8/17;
                    justify-self: center;
                    align-self: center;
                }
                .dateLabel {
                    grid-row: 5/7;
                    grid-column: 8/13;
                    width: 100%;
                    justify-self: center;
                    align-self: center;
                }
                #name {
                    grid-row: 4/5;
                    grid-column: 8/17;
                    width: 100%;
                    justify-self: center;
                    align-self: center;
                }
                #type {
                    grid-row: 5/7;
                    grid-column: 13/17;
                    width: 100%;
                    justify-self: center;
                    align-self: center;
                }
                #location {
                    grid-row: 7/8;
                    grid-column: 8/17;
                    width: 100%;
                    justify-self: center;
                    align-self: center;
                }
            }
        }
    }
</style>
