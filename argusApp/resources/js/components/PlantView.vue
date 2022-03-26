<template>
    <div>
        <h1>PLANT VIEW</h1>
        <PlantInfoModal v-if="plantInfoOpen" @closePlantInfo="togglePlantInfo"/>

            <p>Light: {{sensor.light}}</p><br/>
            <p>Moisture: {{sensor.moisture}}</p><br/>
            <p>Gas: {{sensor.gas}}</p><br/>
            <p>Temperature: {{sensor.temp}} degrees celcius</p><br/>

        <button @click="togglePlantInfo">More Plant Info</button>
    </div>
</template>

<script>

    import PlantInfoModal from './partials/plantView/PlantInfoModal.vue';

    export default {

        data() {
            return {
                plantInfoOpen: false,
            }
        },

        computed: {
            sensor() {
                return this.$store.state.currentReading;
            }
        },

        components: {
            PlantInfoModal
        },

        methods: {
            togglePlantInfo() {
                this.plantInfoOpen = !this.plantInfoOpen;
            }
        }

    };
</script>
