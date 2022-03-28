<template>
    <div>
        <h1>PLANT VIEW</h1>
        <PlantInfoModal v-if="plantInfoOpen" @closePlantInfo="togglePlantInfo"/>

            <p>Light: {{sensor.light}}</p><br/>
            <p>Moisture: {{sensor.moisture}}</p><br/>
            <p>Gas: {{sensor.gas}}</p><br/>
            <p>Temperature: {{sensor.temp}} degrees celcius</p><br/>

        <button @click="togglePlantInfo">More Plant Info</button><br/><br/><br/><br/>

        <a href="#" @click="getChartData('gas')">Gas Readings</a>
        <a href="#" @click="getChartData('light')">Light Readings</a>
        <a href="#" @click="getChartData('temp')">Temperature Readings</a>
        <a href="#" @click="getChartData('moisture')">Moisture Readings</a>

        <Chart v-if="chartData" :chartData="chartData" chartType="LineChart"/>

        <Chart v-if="chartDataMonth" :chartData="chartDataMonth" chartType="ColumnChart"/>

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
    import Chart from './partials/charts/Chart.vue';

    export default {

        data() {
            return {
                plantInfoOpen: false,
                chartData: false,
                chartDataMonth: false,
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
            PlantInfoModal,
            Chart
        },

        methods: {
            togglePlantInfo() {
                this.plantInfoOpen = !this.plantInfoOpen;
            },


            getChartData(sensorType) {

                let dayReadings = this.$store.state.dayTrend;
                let monthReadings = this.$store.state.monthTrend;
                let unit;
                let sensorName = sensorType[0].toUpperCase() + sensorType.slice(1).toLowerCase();

                if (sensorType != "temp") {
                    unit = "Percent";
                } else {
                    unit = "Temperature (°C)"
                }


                let dayChartData = [[unit, sensorName, 'Average']];
                let dayAvg = [];

                dayReadings.forEach(item => {
                    dayAvg.push(item[sensorType]);
                    let point = [item.recorded_at, item[sensorType]];
                    dayChartData.push(point);
                })

                dayAvg = dayAvg.reduce((b, a) => b + a, 0) / dayAvg.length;

                dayChartData.forEach(item => {
                    if (item.length < dayChartData[0].length) {
                        item.push(dayAvg);
                    }
                });

                let monthChartData = [[unit, sensorName]];

                monthReadings.forEach(item => {
                    let point = [item.recorded_at, item[sensorType]];
                    monthChartData.push(point);
                });

                let options = {};

                options.title = `${sensorName} Readings: 24 Hour Trend`;
                options.vAxis = {title: 'Time of Reading'};
                options.hAxis = {title: unit};
                options.colors = ['green', 'orange'];
                options.width = 1200,
                options.height = 700

                let monthOptions = {};

                monthOptions.title = `${sensorName} Readings: 30 Day Trend`;
                monthOptions.hAxis = {title: 'Date of Reading'};
                monthOptions.vAxis = {title: unit};
                monthOptions.colors = ['green', 'orange'];
                monthOptions.width = 1200,
                monthOptions.height = 700

                let dayData = {
                    chartData: dayChartData,
                    options: options,
                }

                let monthData = {
                    chartData: monthChartData,
                    options: monthOptions,
                }

                this.chartData = dayData;
                this.chartDataMonth = monthData;
            },
        }

    };
</script>
