<template>
        <div id="plantProfileCardContain">
            <h2 class="invisibleHeader">Plant Profile Summary Info Card</h2>

             <div v-if="showDeletePlant" class="popupDelete">
                    <p>Are you sure you want to delete this plant?</p>
                    <button @click="deletePlant">Confirm Delete</button>
                    <button @click="showDeleteToggle">Keep Plant</button>
                </div>

            <form ref="plantEditForm" class="plantProfileCard" enctype="multipart/form-data">

                <!-- 4 -->
                <div class="plantProfileEditImage" :style="{'background-image': `url(${currentPlant.image})`}">

                     <div class="saveEdit" @click="editPlant">
                        <span class="fa-stack faWrapper">
                            <i class="fa circle fa-stack-2x"></i>
                            <i v-if="editingPlant" class="fa save fa-stack-1x"></i>
                            <i v-else class="fa edit fa-stack-1x"></i>
                        </span>
                    </div>

                    <!-- 2 -->
                    <div class="deletePlantButton" @click="showDeleteToggle">
                        <span class="fa-stack faWrapper">
                            <i class="fa circle fa-stack-2x"></i>
                            <i class="fa delete fa-stack-1x"></i>
                        </span>
                    </div>

                    <div v-if="editingPlant" class="editingButtonsPlantImg">

                            <label for="imagePlant" class="editPlantImage">
                                    <span class="fa-stack faWrapper">
                                        <i class="fa circle fa-stack-2x"></i>
                                        <i class="fa edit fa-stack-1x"></i>
                                    </span>
                                <span class="editPlantImgTxt">{{plantEditFileLoaded ? 'file uploaded!' : 'update image'}}</span>
                            </label>
                            <input type="file" @change="updatePlantFile" class="form-control" name="image" id="plantViewImage" hidden/>

                        <div @click="toggleEdit" class="revertNoSaveWrap">
                            <span class="fa-stack faWrapper">
                                <i class="fa circle fa-stack-2x"></i>
                                <i class="fa logout fa-stack-1x"></i>
                            </span>
                            <span class="revertNoSave">stop editing</span>
                        </div>
                    </div>

                </div>

                <!-- 5 -->
                <p class="plantNameProfCard"><span class="font-awesome-icons plant"></span> {{currentPlant.name}}</p>

                <!-- 6 -->
                <div class="plantProfileSummary">
                    <div v-if="editingPlant">
                        <p class="plantLocationEdit"><strong>location:</strong></p>
                        <input type="text" name="location" id="plantLocationPlantView" :placeholder="currentPlant.location"/>
                    </div>
                    <p v-else><strong>location:</strong> {{currentPlant.location}}</p>

                    <!-- 7 -->
                    <p><strong>type:</strong> {{currentPlant.type}}</p>

                    <!-- 8 -->
                    <p><strong>age:</strong> {{currentPlant.age}}</p>
                </div>

            </form>
        </div>
</template>
<script>
    export default {
    props: ["currentPlant"],
    data() {
      return {
        editingPlant: false,
        showDeletePlant: false,
        plantEditFileLoaded: false,
      }
    },
    methods: {

        updatePlantFile() {
            this.plantEditFileLoaded = !this.plantEditFileLoaded;
        },

       toggleEdit() {
          this.editingPlant = !this.editingPlant;
        },

        editPlant() {

            if (!this.editingPlant) {
                    this.toggleEdit();
                return;
            }

            const PlantEditFormData = new FormData(this.$refs.plantEditForm);

            PlantEditFormData.append('_method', 'PATCH');
            PlantEditFormData.append('edit_profile_card', true);
            PlantEditFormData.append('locationB', this.currentPlant.location);

            axios.post(`/api/plants/${this.currentPlant.id}`, PlantEditFormData)

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
            .catch(error => {
                console.log(error);
            })
            .then(() => {
                this.editingPlant = false; //you were here
            });

        },

        showDeleteToggle() {
          this.showDeletePlant = !this.showDeletePlant;
        },

        deletePlant() {

           const PlantDelete = new FormData();

            PlantDelete.append('_method', 'DELETE');

           axios.post(`/api/plants/${this.currentPlant.id}`, PlantDelete)

            .then(response => {
                return response.data;
            })
            .then(() => {
                return axios.get(`/api/plants/${this.$store.state.user.id}`);
            })
            .then(response => {
                // the second call is made to pull ALL plants fresh from the db (with updated plant) and commit them to the store
                this.$store.commit('userPlants', response.data);
                this.$router.push({name: 'home'});
                this.showDeleteToggle();
            })
            .catch(error => {
                console.log(error);
            })
        },
    }

    };
</script>

<style lang="scss">
 @import "../../../.././sass/variables/breakpoints.scss";
    @import "../../../.././sass/variables/fonts.scss";
    @import "../../../.././sass/variables/colors.scss";
    @import "../../../.././sass/mixins/all-mixins.scss";
    #plantProfileCardContain {
        display: inline-block;
        margin: 0 auto;
        width: 16rem;
        .plantProfileCard {
            margin: 0 auto;
            display: grid;
            grid-template-rows: repeat(12, 1fr);
            grid-template-columns: repeat(6, 1fr);
            background-color: $green;
            border-radius: 10px;
            width: 100%;
            height: 30rem;
            margin-bottom: 1em;
            .plantProfileEditImage {
                grid-row: 1/8;
                grid-column: 1/7;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                width: 100%;
                border-radius: 10px 10px 0 0;
                position: relative;
                display: grid;
                grid-template-rows: repeat(12, 1fr);
                grid-template-columns: repeat(6, 1fr);
                .saveEdit {
                    grid-row: 1/3;
                    grid-column: 1/2;
                    justify-self: center;
                    align-self: center;
                    cursor: pointer;
                    .faWrapper {
                        .circle::before {
                            @include awesomeIcon($white, 'circle');
                        }
                        .edit::before {
                            @include awesomeIcon($purple, 'edit');
                        }
                        .save::before {
                            @include awesomeIcon($purple, 'save');
                        }
                    }
                }
                .deletePlantButton {
                    grid-row: 1/3;
                    grid-column: 6/7;
                    justify-self: center;
                    align-self: center;
                    cursor: pointer;
                    .faWrapper {
                        .circle::before {
                            @include awesomeIcon($white, 'circle');
                        }
                        .delete::before {
                            @include awesomeIcon($purple, 'delete');
                        }
                    }
                }
                .editingButtonsPlantImg {
                    grid-row: 6/11;
                    grid-column: 2/7;
                    width: 95%;
                    width: 95%;
                    justify-self: center;
                    align-self: center;
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                    gap: 1em;
                    .editPlantImage {
                        background-color: $bg;
                        padding: 0.5em 1em;
                        border-radius: 10px;
                        cursor: pointer;
                        display: inline-block;
                        .faWrapper {
                            .circle::before {
                                @include awesomeIcon($white, 'circle');
                            }
                            .edit::before {
                                @include awesomeIcon($purple, 'edit');
                            }
                        }
                        .editPlantImgTxt {
                            font-size: $body-mobile;
                            color:$purple;
                            &:hover {
                                text-decoration: underline;
                            }
                        }
                    }
                    .revertNoSaveWrap {
                        background-color: $bg;
                        padding: 0.5em 1em;
                        border-radius: 10px;
                        cursor: pointer;
                        .faWrapper {
                            .circle::before {
                                @include awesomeIcon($white, 'circle');
                            }
                            .logout::before {
                                @include awesomeIcon($purple, 'logout');
                            }
                        }
                        .revertNoSave {
                            font-size: $body-mobile;
                            color:$purple;
                            &:hover {
                                text-decoration: underline;
                            }
                        }
                    }
                }
            }
            .plantNameProfCard {
                grid-row: 7/9;
                grid-column: 1/7;
                background: $white;
                border-radius: 10px;
                padding: 0.5em;
                color: $purple;
                justify-self: center;
                align-self: center;
                z-index: 400;
                font-size: $card-mobile;
                .plant::before {
                    @include awesomeIcon($purple, 'plant');
                }
            }
            .plantProfileSummary {
                grid-row: 8/13;
                grid-column: 1/7;
                border-radius: 0 0 2em 2em;
                width: 100%;
                height: 75%;
                justify-self: center;
                align-self: center;
                color: $white;
                display: flex;
                flex-direction: column;
                justify-content: space-around;
                text-align: center;
                p {
                    font-size: $body-tablet;
                }
                .plantLocationEdit {
                    display: inline-block;
                    width: auto;
                }
                input {
                    max-width: 40%;
                    padding: 0.5em;
                }
            }
        }
    }

   @media (min-width: $tablet-break) {
       #plantProfileCardContain {
            display: inline-block;
            margin: 0 auto;
            width: 15rem;
            .plantProfileCard {
                margin: 0 auto;
                display: grid;
                grid-template-rows: repeat(12, 1fr);
                grid-template-columns: repeat(6, 1fr);
                background-color: $green;
                border-radius: 10px;
                width: 100%;
                height: 28rem;
                .plantProfileEditImage {
                    grid-row: 1/8;
                    grid-column: 1/7;
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: cover;
                    width: 100%;
                    border-radius: 10px 10px 0 0;
                    position: relative;
                    display: grid;
                    grid-template-rows: repeat(12, 1fr);
                    grid-template-columns: repeat(6, 1fr);
                    .saveEdit {
                        grid-row: 1/3;
                        grid-column: 1/2;
                        justify-self: center;
                        align-self: center;
                        cursor: pointer;
                        .faWrapper {
                            .circle::before {
                                @include awesomeIcon($white, 'circle');
                            }
                            .edit::before {
                                @include awesomeIcon($purple, 'edit');
                            }
                            .save::before {
                                @include awesomeIcon($purple, 'save');
                            }
                        }
                    }
                    .deletePlantButton {
                        grid-row: 1/3;
                        grid-column: 6/7;
                        justify-self: center;
                        align-self: center;
                        cursor: pointer;
                        .faWrapper {
                            .circle::before {
                                @include awesomeIcon($white, 'circle');
                            }
                            .delete::before {
                                @include awesomeIcon($purple, 'delete');
                            }
                        }
                    }
                    .editingButtonsPlantImg {
                        grid-row: 6/11;
                        grid-column: 2/7;
                        width: 95%;
                        width: 95%;
                        justify-self: center;
                        align-self: center;
                        display: flex;
                        flex-direction: column;
                        align-items: flex-end;
                        gap: 1em;
                        .editPlantImage {
                            background-color: $bg;
                            padding: 0.5em 1em;
                            border-radius: 10px;
                            cursor: pointer;
                            display: inline-block;
                            .faWrapper {
                                .circle::before {
                                    @include awesomeIcon($white, 'circle');
                                }
                                .edit::before {
                                    @include awesomeIcon($purple, 'edit');
                                }
                            }
                            .editPlantImgTxt {
                                font-size: $body-mobile;
                                color:$purple;
                                &:hover {
                                    text-decoration: underline;
                                }
                            }
                        }
                        .revertNoSaveWrap {
                            background-color: $bg;
                            padding: 0.5em 1em;
                            border-radius: 10px;
                            cursor: pointer;
                            .faWrapper {
                                .circle::before {
                                    @include awesomeIcon($white, 'circle');
                                }
                                .logout::before {
                                    @include awesomeIcon($purple, 'logout');
                                }
                            }
                            .revertNoSave {
                                font-size: $body-mobile;
                                color:$purple;
                                &:hover {
                                    text-decoration: underline;
                                }
                            }
                        }
                    }
                }
                .plantNameProfCard {
                    grid-row: 7/9;
                    grid-column: 1/7;
                    background: $white;
                    border-radius: 10px;
                    padding: 0.5em;
                    color: $purple;
                    justify-self: center;
                    align-self: center;
                    z-index: 400;
                    font-size: $card-mobile;
                    .plant::before {
                        @include awesomeIcon($purple, 'plant');
                    }
                }
                .plantProfileSummary {
                    grid-row: 8/13;
                    grid-column: 1/7;
                    border-radius: 0 0 2em 2em;
                    width: 100%;
                    height: 75%;
                    justify-self: center;
                    align-self: center;
                    color: $white;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-around;
                    text-align: center;
                    p {
                        font-size: $body-tablet;
                    }
                    .plantLocationEdit {
                        display: inline-block;
                        width: auto;
                    }
                    input {
                        max-width: 40%;
                        padding: 0.5em;
                    }
                }
            }
        }
    }
    @media (min-width: $desktop-break) {
         #plantProfileCardContain {
            display: inline-block;
            margin: 0 auto;
            width: 24rem;
            .plantProfileCard {
                margin: 0 auto;
                display: grid;
                grid-template-rows: repeat(12, 1fr);
                grid-template-columns: repeat(6, 1fr);
                background-color: $green;
                border-radius: 10px;
                width: 100%;
                height: 34rem;
        }
    }
    }

</style>