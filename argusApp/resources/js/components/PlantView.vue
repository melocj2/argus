<template>
    <div>
        <h1>PLANT VIEW</h1>
        <PlantInfoModal v-if="plantInfoOpen" @closePlantInfo="togglePlantInfo"/>

            <p>Light: {{sensor.light}}</p><br/>
            <p>Moisture: {{sensor.moisture}}</p><br/>
            <p>Gas: {{sensor.gas}}</p><br/>
            <p>Temperature: {{sensor.temp}} degrees celcius</p><br/>

        <button @click="togglePlantInfo">More Plant Info</button><br/><br/><br/><br/>

        <div id="chartContainer">
            <Chart class="barChart" :chartData="chartData.sensors" chartType="BarChart"/>

            <Chart class="columnChart" :chartData="chartData.summary" chartType="ColumnChart"/>
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
            }
        },

        computed: {


            sensor() {
                return this.$store.state.currentReading;
            },

            chartData() {

                //check what the current chartShown property is

                let dayTrendData = this.$store.state.dayTrend;

                let monthTrendData = this.$store.state.monthTrend;

                let dayData = this.getHealthAvgs(dayTrendData);

                let monthData = this.getHealthAvgs(monthTrendData);

                if (!(dayData || monthData)) {
                    return false;
                }

                let info = [dayData, monthData];
                let data = {};

                data.sensors = this.convertToChartData(dayData, monthData); //should return 1 array, with both one day and one 30 day data, each with chart data for broken up gas, temp, light, and moisture;

                data.summary = this.getHealthSummary(dayData, monthData); //should return 2 arrays, one day and one 30 day, each with chart data for summary of 30 day and one day;

                data.info = info;

                console.log('into Chart', data);
               return data;

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

            getHealthAvgs (arr) {

                let names = [...Object.keys(this.$store.state.currentReading)];
                let healthValues = [];
                let finalObj = {};

                names.forEach(item => {
                    healthValues.push([]);
                })

                if (arr.length > 0) {
                    //return single object with average of sensor values as health value

                    arr.forEach(item => {
                        names.forEach(name => {
                            if (item[name]) {
                                healthValues[names.indexOf(name)].push(item[name]);
                            }
                        })
                    });

                    healthValues.forEach((item, index) => {
                        finalObj[names[index]] = this.getHealthScore(item.reduce((b, a) => b + a, 0) / item.length, names[index]);
                    });

                } else {
                    return false;
                }

                console.log('getHealthAvgs Output', finalObj);
                return finalObj;

            },

            getHealthScore(raw, name) {

                let score;

                if (name == 'temp') {
                    if (raw <= 24 && raw >= 15) {
                        score = 100;
                    } else if (raw > 24){
                        score = 100 - Math.round((100 * (raw-24)) / (45-24));
                    } else if (raw < 15){
                        score = 100 - Math.round((100 * raw) / 15);
                    } else {
                        return false;
                    }
                } else if (name == 'gas'){
                    if (raw <= 30) {
                        score = 100;
                    } else if (raw <= 50) {
                        score = 90;
                    } else if (raw > 80) {
                        score = 0;
                    } else if (raw > 50) {
                        score = Math.round(((100 - (100 * (raw-50)) / (80-50)) / 100) * 80);
                    } else {
                        return false;
                    }
                } else if (name == 'light') {
                    if (raw >= 20 && raw <= 70) {
                        score = 100;
                    } else if (raw >= 4 && raw <= 86) {
                        score = 90;
                    } else if (raw < 4) {
                        score = Math.round(((100 - (100 * raw) / 4) / 100) * 90);
                    } else if (raw > 86) {
                        score = Math.round(((100 - (100 * (raw-86)) / (100-86)) / 100) * 90);
                    } else {
                        return false;
                    }
                } else if (name ==='moisture') {
                    if (raw >= 50 && raw <= 80) {
                        score = 100;
                    } else if (raw >= 60 && raw <= 90) {
                        score = 90;
                    } else if (raw < 40) {
                        score = 0;
                    } else if (raw < 60) {
                        score = Math.round(((100 - (100 * (raw-40)) / (100-60)) / 100) * 90);
                    } else if (raw > 90) {
                        score = Math.round(((100 - (100 * (raw-90)) / (100-90)) / 100) * 90);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

                console.log('getHealthScore Output', score);
                return score;

            },

            convertToChartData(objA, objB) {


                let chartData = [

                    ['plant health', 'gas', {role: 'style'}, { role: 'annotation' }, 'temperature', {role: 'style'}, { role: 'annotation' }, 'light', {role: 'style'},  { role: 'annotation' }, 'moisture', {role: 'style'}, { role: 'annotation' }],
                    ['24 hrs', objA.gas, 'color: #624972; opacity: 0.4;', 'gas', objA.temp, 'color: #624972; opacity: 0.6', 'temperature', objA.light, 'color: #624972; opacity: 0.8', 'light', objA.moisture, 'color: #624972', 'moisture'],
                    ['30 days', objB.gas, 'color: #597249; opacity: 0.4', 'gas', objB.temp, 'color: #597249; opacity: 0.6', 'temperature', objB.light, 'color: #597249; opacity: 0.8', 'light', objB.moisture, 'color: #597249', 'moisture']

                    ];

                let options = {};

                options.width = 600
                options.height = 900
                options.legend = 'none'
                options.vAxis = {
                    gridlines: {
                        color: 'black'
                    }
                }
                options.bar = {gap: '10%', groupWidth: '40%'}
                options.hAxis = {
                    title: 'health rating',
                    color: "#000",
                    viewWindow: {
                        max: 100,
                        min: 0,
                    },

                    // gridlines: {
                    //     color: 'transparent'
                    //  }
                }
                options.vAxis = {
                    title: 'time frame'
                }

                let finalProduct = {chartData: chartData, options: options};

                console.log("chartDataHere", finalProduct);

                return finalProduct;

            },

            //takes a sensors object and sums all values
            quickSum(arr) {
                let sum = arr.reduce((a, b) => a + b, 0);
                return sum;
            },


            getHealthSummary(objA, objB) {



                let obj = {};

                let daySum = this.quickSum([objA.moisture, objA.light, objA.temp, objA.gas]);

                let monthSum = this.quickSum([objB.moisture, objB.light, objB.temp, objB.gas]);

                obj.day = (100 * daySum) / 400;
                obj.month = (100 * monthSum) / 400;

                 let chartData = [

                    ['plant health', 'summary', {role: 'style'}],
                    ['24 hrs', obj.day, 'color: #624972; opacity: 0.9'],
                    ['30 days', obj.month, 'color: #597249; opacity: 0.9']

                    ];

                let options = {};

                options.width = 700,
                options.height = 900,
                options.bar = {width: '40%'}
                options.legend = 'none',
                options.vAxis = {
                    title: 'health rating',
                    viewWindow: {
                        max: 100,
                        min: 0,
                    }
                },
                options.hAxis = {
                    title: 'time frame',
                }

                let finalProduct = {chartData: chartData, options: options};

                console.log("chartDataHere MONTHLY", finalProduct);

                return finalProduct;
            }


        }

    };
</script>

<style lang="scss">
@import "../.././sass/variables/breakpoints.scss";
@import "../.././sass/variables/fonts.scss";
@import "../.././sass/variables/colors.scss";
#chartContainer {
    width: 100%;
    display: flex;
    justify-content: center;
    align-content: center;
    gap: 20%;
    .barChart {
        display: inline-block;
        align-self: flex-start;
    }
    .columnChart {
        display: inline-block;
        align-self: flex-end;
    }

}

</style>
