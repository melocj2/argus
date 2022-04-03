<template>
    <div class="plantInfoBlanket">
        <div class="plantInfoDetail" v-if="addedPlantInfo">
            <h1>PLANT INFO MODAL</h1>
            <p>scientific name: {{this.addedPlantInfo.species}}</p>
            <p>age: {{this.plant.age}}</p>
            <p>description: {{this.addedPlantInfo.info}}</p>
            <button @click="closePlantInfo">close</button>
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



}
</script>

<style lang="scss">
    .plantInfoBlanket {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
        background-color: rgba(255, 255, 255, 0.644);
        z-index: 100;
        .plantInfoDetail {
            position: fixed;
            top: 5vh;
            width: 80vw;
            left: 5vw;
            max-height: 90vh;
            overflow-y: scroll;
            // transform: translate(-50%, -90%);
            background-color: rgb(216, 216, 216);
            padding: 2em 5em;
            z-index: 1000;
        }
    }
</style>
