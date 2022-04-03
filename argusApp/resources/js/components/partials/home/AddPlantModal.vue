<template>
    <div class="addPlantBlanket">
        <div class="addPlantModalForm">
            <h1>ADD PLANT MODAL</h1>
            <p>ADD NEW PLANT INFO FORM HERE</p>
            <button @click="closeAddPlant"><span class="font-awesome-icons close"></span></button>
            <form ref="plantForm" enctype="multipart/form-data">
            <input type="hidden" name="upload_plant" value="1" />
            <p>name: </p>
            <input type="text" class="form-control" name="name" id="name"/>
            <p>type: </p>
            <input type="text" class="form-control" name="type" id="type" />
            <p>image: </p>
            <input type="file" class="form-control" name="image" id="image" />
            <p>date planted (estimates are fine): </p>
            <input type="date" class="form-control" name="date_planted" id="datePlanted" :value="currentDate" min="2000-01-01" :max="currentDate">
            <p>location: </p>
            <input type="text" class="form-control" name="location" id="location" />

            <div v-if="errors" >

                    <p>{{errors}}</p>

            </div>
            <button id="addPlantButton" @click="addPlant" :disabled="disabledAdd">
                <span class="font-awesome-icons add-alt"></span> add plant
            </button>
          </form>

        </div>
    </div>
</template>

<script>
    export default {

    data() {
        return {
            currentDate: new Date().toISOString().slice(0, 10),
            disabledAdd: null,
            errors: null,
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

        sendSMS() {

             axios.get('/api/sensors/sendAlert')
                .then(response => {
                    console.log('ALERT SENT', response.data);
                }).catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>

<style lang="scss">
    .addPlantBlanket {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
        background-color: rgba(255, 255, 255, 0.644);
        z-index: 100;
        .addPlantModalForm {
            position: fixed;
            top: 90%;
            left: 50%;
            transform: translate(-50%, -90%);
            background-color: rgb(216, 216, 216);
            padding: 2em 5em;
            z-index: 1000;
        }
    }
</style>
