<template>
    <div class="addPlantBlanket">
        <div class="addPlantModalForm">
            <h1>ADD PLANT MODAL</h1>
            <p>ADD NEW PLANT INFO FORM HERE</p>
            <button @click="closeAddPlant">close</button>
        </div>
    </div>
</template>

<script>
    export default {

    methods: {
        closeAddPlant() {
            return this.$emit('closeAddPlant');
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
            top: 50%;
            left: 50%;
            transform: translate(-50%, -90%);
            background-color: rgb(216, 216, 216);
            padding: 2em 5em;
            z-index: 1000;
        }
    }
</style>
