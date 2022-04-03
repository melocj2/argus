<template>
    <div class="plantProfileCard">
        <div>
            <form ref="plantEditForm" enctype="multipart/form-data">
                <button type="button" v-if="editingPlant" id="editPlantButton" @click="editPlant">
                    <span class="font-awesome-icons save"></span>
                </button>
                <button type="button" v-else @click="toggleEdit"><span class="font-awesome-icons edit"></span></button>
                <button type="button" v-if="editingPlant" @click="toggleEdit">Back</button>
                <button type="button" v-if="!showDeletePlant" @click="showDeleteToggle"><span class="font-awesome-icons delete"></span></button>
                <div v-else>
                    <p>Are you sure?</p>
                    <button @click="deletePlant">Confirm Delete</button>
                    <button @click="showDeleteToggle">Keep Plant</button>
                </div>
                <div class="plantProfileImage" :style="{'background-image': `url(${currentPlant.image})`}"></div>
                <div v-if="editingPlant">
                    <p>upload new image:</p>
                    <input type="file" class="form-control" name="image" id="plantViewImage"/>
                </div>
                   <p><span class="font-awesome-icons plant"></span> {{currentPlant.name}}</p>
                    <input v-if="editingPlant" type="text" name="location" id="plantLocationPlantView" :placeholder="currentPlant.location"/>
                    <p v-else>location: {{currentPlant.location}}</p>
                <p>plant type: {{currentPlant.type}}</p>
                <p>age: {{currentPlant.age}}</p>
            </form>
        </div>
    </div>
</template>
<script>
    export default {
    props: ["currentPlant"],
    data() {
      return {
        editingPlant: false,
        showDeletePlant: false,
      }
    },
    methods: {
       toggleEdit() {
          this.editingPlant = !this.editingPlant;
        },

        editPlant() {

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
  .plantProfileCard {
    display: inline-block;
    width: 30rem;
  }
</style>