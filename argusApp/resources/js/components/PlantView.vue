<template>
    <div id="plantViewPage">
        <h2 class="invisibleHeader">Plant Profile Page</h2>
        <PlantListMenu />
        <div class="plantViewLogo" :style="{'background-image': `url(/images/logoP.svg)`}"></div>
        <div class="mainPlantViewContainer">

                <PlantProfileCard :currentPlant="currentPlant" v-if="currentPlant" class="plantProfileCard"/>

               <button class="morePlantInfoBtn" @click="togglePlantInfo"><span class="font-awesome-icons info"></span> more plant info</button>

               <button class="chartModalBtn" @click="toggleChartModal"><span class="font-awesome-icons info"></span> charts</button>

                <SensorCardDealer :cardHealthScores="cardHealthScores" v-if="currentPlant" class="sensorCardDealer" />



            <PlantInfoModal v-if="plantInfoOpen" @closePlantInfo="togglePlantInfo" :currentPlant="currentPlant" class="plantInfoOpen" />

            <ChartModal v-if="chartModalOpen" @closeChartModal="toggleChartModal" :chartData="chartData" class="chartModalOpen"/>


        </div>


    </div>
</template>

<script>

    import PlantInfoModal from './partials/plantView/PlantInfoModal.vue';
    import Chart from './partials/charts/Chart.vue';
    import PlantProfileCard from './partials/plantView/PlantProfileCard.vue';
    import PlantListMenu from './partials/plantView/PlantListMenu.vue';
    import SensorCardDealer from './partials/plantView/SensorCardDealer.vue';
    import ChartModal from './partials/plantView/ChartModal.vue';

    export default {

         components: {
            PlantInfoModal,
            Chart,
            PlantProfileCard,
            PlantListMenu,
            SensorCardDealer,
            ChartModal
        },

        data() {
            return {
                plantInfoOpen: false,
                chartModalOpen: false,
                windowWidth: false,
            }
        },

        computed: {

            currentPlant() {

                if(this.$store.state.userPlants) {

                    let allPlants = this.$store.state.userPlants;

                    let currentPlant = allPlants.filter(item => {
                        return item.id == this.$route.params.id;
                    });

                    return currentPlant[0];

                }

                return false

            },

            cardHealthScores() {

                let readings = this.$store.state.currentReading;

                if (!readings) {
                    return false;
                }

                let currentHealth = {};

                currentHealth.temp = this.getHealthScore(readings.temp, 'temp');

                currentHealth.light = this.getHealthScore(readings.light, 'light');

                currentHealth.gas = this.getHealthScore(readings.gas, 'gas');

                currentHealth.moisture = this.getHealthScore(readings.moisture, 'moisture');

                return currentHealth;
            },


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

                let data = [];

                data.push({
                    data: this.convertToChartData(dayData, monthData),
                    type: 'BarChart',
                    id: 1,
                    title: "health score by sensor"
                });

                data.push({
                    data: this.getHealthSummary(dayData, monthData),
                    type: 'ColumnChart',
                    id: 2,
                    title: "summary of health data"
                });

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

        methods: {
            togglePlantInfo() {
                if (document.querySelector("body").style.overflow != 'hidden') {
                    document.querySelector("body").style.overflow = 'hidden';

                } else {
                    document.querySelector("body").style.overflow = 'auto';
                }
                this.plantInfoOpen = !this.plantInfoOpen;
            },

            toggleChartModal() {
                if (document.querySelector("body").style.overflow != 'hidden') {
                    document.querySelector("body").style.overflow = 'hidden';

                } else {
                    document.querySelector("body").style.overflow = 'auto';
                }
                this.chartModalOpen = !this.chartModalOpen;
            },

            getHealthAvgs (arr) {

                let names = [...Object.keys(this.$store.state.currentReading)];
                let healthValues = [];
                let finalObj = {};

                names.forEach(item => {
                    healthValues.push([]);
                })

                if (arr.length > 0) {


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

                return finalObj;

            },

            getHealthScore(raw, name) {

                let score;

                //thinking we could get a percentage score for this, and send alerts

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
                    ['24 hrs', objA.gas, 'color: #624972; opacity: 0.4;', 'gas', objA.temp, 'color: #624972; opacity: 0.6', 'temp.', objA.light, 'color: #624972; opacity: 0.8', 'light', objA.moisture, 'color: #624972', 'moisture'],
                    ['30 days', objB.gas, 'color: #597249; opacity: 0.4', 'gas', objB.temp, 'color: #597249; opacity: 0.6', 'temp.', objB.light, 'color: #597249; opacity: 0.8', 'light', objB.moisture, 'color: #597249', 'moisture']

                ];

                let options = {};

                let dimensions = [];

                 if (this.isMobileScreen()) {
                    dimensions.push(360);
                    dimensions.push(280);
                } else if (this.isTabletScreen()) {
                    dimensions.push(400);
                    dimensions.push(320);
                } else if (this.isDesktopScreen()) {
                    dimensions.push(400);
                    dimensions.push(330);
                }

                options.width = dimensions[0];
                options.height = dimensions[1];
                options.backgroundColor = {fill: '#f7f7f7'}; //this is $bg
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

                let dimensions = [];

                if (this.isMobileScreen()) {
                    dimensions.push(360);
                    dimensions.push(280);
                } else if (this.isTabletScreen()) {
                    dimensions.push(400);
                    dimensions.push(320);
                } else if (this.isDesktopScreen()) {
                    dimensions.push(400);
                    dimensions.push(330);
                }

                options.width = dimensions[0];
                options.height = dimensions[1];
                options.bar = {width: '40%'}
                options.legend = 'none';
                options.backgroundColor = {fill: '#f7f7f7'}; //this is $bg
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
            },

            isMobileScreen() {
                let isMobile = this.windowWidth < 700;
                if (isMobile) {
                    this.hamburgOpen = false;
                }
                return isMobile;
            },

            isTabletScreen() {
                let isTablet = (this.windowWidth >= 700 && this.windowWidth < 1200);
                if (isTablet) {
                    this.hamburgOpen = false;
                }
                return isTablet;
            },

            isDesktopScreen() {
                let isTablet = this.windowWidth >= 1200;
                if (isTablet) {
                    this.hamburgOpen = false;
                }
                return isTablet;
            },
        },
        mounted() {
            this.windowWidth = window.innerWidth;
            window.addEventListener('resize', () => {
                this.windowWidth = window.innerWidth;
            })
        }
    };
</script>

<style lang="scss">
@import "../.././sass/variables/breakpoints.scss";
@import "../.././sass/variables/fonts.scss";
@import "../.././sass/variables/colors.scss";
@import "../.././sass/mixins/all-mixins.scss";


      #plantViewPage {
            width: 100%;
             .plantViewLogo {
                background-position: center;
                background-repeat: no-repeat;
                background-size: contain;
                height: 3rem;
                width: 100%;
                border-radius: 10px;
                background-color: transparent;
                margin-right: 0;
                margin-top: 1em;
                margin-bottom: 2em;
            }
            .mainPlantViewContainer {
                width: 16rem;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                .morePlantInfoBtn, .chartModalBtn {
                    @include buttonStyle;
                    @include border;
                    padding: 1em 2em;
                    margin-bottom: 1em;
                    box-sizing: border-box;
                    cursor: pointer;
                    .info::before {
                        @include awesomeIcon($black, 'info');
                    }
                }
            }
        }


    @media (min-width: $tablet-break) {
        #plantViewPage {
            display: flex;
            flex: 12rem 1fr;
            .plantViewLogo {
                display: none;
            }
            .mainPlantViewContainer {
                width: 100%;
                height: 80%;
                min-width: 850px;
                justify-self: center;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(18, 1fr);
                .plantProfileCard {
                    grid-row: 2/11;
                    grid-column: 1/2;
                    align-self: center;
                    justify-self: center;
                }
                .morePlantInfoBtn {
                    grid-row: 11/12;
                    grid-column: 1/2;
                    align-self: center;
                    justify-self: center;
                    width: 15rem;
                }
                .chartModalBtn {
                    grid-row: 12/13;
                    grid-column: 1/2;
                    align-self: center;
                    justify-self: center;
                    width: 15rem;
                }
                .sensorCardDealer {
                    grid-row: 2/13;
                    grid-column: 2/4;
                    align-self: center;
                    justify-self: center;
                }
            }
        }
    }


@media (min-width: $desktop-break) {
        #plantViewPage {
            .plantViewLogo {
                display: none;
            }
            .mainPlantViewContainer {

                // height: 80%;
                margin: 0 auto;
                width: 1260px;
                min-width: 1240px;
                height: calc(100vh - 7em);
                min-height: 900px;
                justify-self: center;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(14, 1fr);
                .plantProfileCard {
                    grid-row: 2/12;
                    grid-column: 1/2;
                    align-self: center;
                    justify-self: center;
                }
                .morePlantInfoBtn {
                    grid-row: 12/13;
                    grid-column: 1/3;
                    align-self: center;
                    justify-self: center;
                    width: 20rem;
                }
                .chartModalBtn {
                    grid-row: 12/13;
                    grid-column: 2/4;
                    align-self: center;
                    justify-self: center;
                    width: 20rem;
                }
                .sensorCardDealer {
                    grid-row: 3/11;
                    grid-column: 2/4;
                    align-self: center;
                    justify-self: center;
                }
            }
        }
    }



</style>
