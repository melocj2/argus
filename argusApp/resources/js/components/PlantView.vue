<template>
    <div>
        <h1>PLANT VIEW</h1>
        <PlantInfoModal v-if="plantInfoOpen" @closePlantInfo="togglePlantInfo"/>

            <p>Light: {{sensor.light}}</p><br/>
            <p>Moisture: {{sensor.moisture}}</p><br/>
            <p>Gas: {{sensor.gas}}</p><br/>
            <p>Temperature: {{sensor.temp}} degrees celcius</p><br/>

        <button @click="togglePlantInfo">More Plant Info</button>
        <div style="float: left;">
            <h2>24 HOUR TREND</h2>
            <ul v-if="dayTrend">
                <li v-for="data in dayTrend" :key="data.id">
                    <p>Light: {{data.light}}</p><br/>
                    <p>Moisture: {{data.moisture}}</p><br/>
                    <p>Gas: {{data.gas}}</p><br/>
                    <p>Temperature: {{data.temp}} degrees celcius</p><br/>
                    <p>Time: {{data.recorded_at}}</p><br/>
                </li>
            </ul>
        </div>
        <div style="float: right;">
        <h2>30 DAY TREND</h2>
            <ul v-if="monthTrend">
                <li v-for="data in monthTrend" :key="data.id">
                    <p>Light: {{data.light}}</p><br/>
                    <p>Moisture: {{data.moisture}}</p><br/>
                    <p>Gas: {{data.gas}}</p><br/>
                    <p>Temperature: {{data.temp}} degrees celcius</p><br/>
                    <p>Time: {{data.recorded_at}}</p><br/>
                </li>
            </ul>
        </div>
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
            },
            dayTrend() {
                let dayTrendData = this.$store.state.dayTrend;
                if (dayTrendData.length > 0) {
                    return dayTrendData;
                } else {
                    return false;
                }
            },
            monthTrend() {
                let monthTrendData = this.$store.state.monthTrend;
                if (monthTrendData.length > 0) {
                    return monthTrendData;
                } else {
                    return false;
                }
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
