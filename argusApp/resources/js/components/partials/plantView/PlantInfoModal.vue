<template>
    <div class="plantInfoBlanket">
        <div class="plantInfoDetail">
            <h1>PLANT INFO MODAL</h1>
            <p>ALL PLANT DATA GOES HERE</p>
            <button @click="closePlantInfo">close</button>
        </div>
    </div>
</template>

<script>
    export default {

    methods: {
        closePlantInfo() {
            return this.$emit('closePlantInfo');
        }
    }
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
            top: 50%;
            left: 50%;
            transform: translate(-50%, -90%);
            background-color: rgb(216, 216, 216);
            padding: 2em 5em;
            z-index: 1000;
        }
    }
</style>
