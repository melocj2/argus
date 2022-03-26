<template>
    <div>
        <h1>HOME</h1>

        <AddPlantModal v-if="addPlantOpen" @closeAddPlant="toggleAddPlant" />
        <button @click="toggleAddPlant">Add Plant</button><br/><br/><br/>

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
            }
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
