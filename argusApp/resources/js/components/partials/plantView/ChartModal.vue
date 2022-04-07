<template>
  <div class="chartBlanket">
    <div class="chartModal">
        <a class="closeChartModal" @click="closeCharts">
            <span class="fa-stack faWrapper">
                <i class="fa circle fa-stack-2x"></i>
                <i class="fa close fa-stack-1x"></i>
            </span>
        </a>
        <div v-for="chart in chartData" :key="chart.id" class="chartWrap">
          <h5 class="chartTitle">{{chart.title}}</h5>
          <Chart :class="chart.type" :chartData="chart.data" :chartType="chart.type" />
        </div>
    </div>
  </div>
</template>

<script>
  import Chart from '.././charts/Chart.vue';
  export default {

    props: ["chartData"],

    components: {
      Chart
    },

    methods: {
        closeCharts() {
            return this.$emit('closeChartModal');
        }
    },

  }

</script>

<style lang="scss">
@import "../../../.././sass/variables/breakpoints.scss";
@import "../../../.././sass/variables/fonts.scss";
@import "../../../.././sass/variables/colors.scss";
@import "../../../.././sass/mixins/all-mixins.scss";

.chartBlanket {
  overflow: hidden;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 100vw;
  background-color: rgba(161, 161, 161, 0.8);;
  z-index: 600;
  .chartModal {
    position: fixed;
    top: 1em;
    width: 23rem;
    height: 40em;
    left: 50%;
    transform: translateX(-50%);
    background-color: $bg;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    .chartWrap {
      .chartTitle {
        color: $purple;
        text-align: center;
        z-index: 700;
      }
    }
    .closeChartModal {
      z-index: 100;
      position: absolute;
      top: 0.5em;
      right: 0.5em;
      cursor: pointer;
      .faWrapper {
        font-size: $card-tablet;
        .circle::before {
            @include awesomeIcon($white, 'circle');
        }
        .close::before {
            @include awesomeIcon($purple, 'close');
        }
      }
    }
  }
}

@media (min-width: $tablet-break) {
  .chartBlanket {
    .chartModal {
      top: 3em;
      width: 35rem;
      height: 45em;
      justify-content: space-around;
    }
  }
}

@media (min-width: $desktop-break) {
  .chartBlanket {
    .chartModal {
      top: 5em;
      width: 60rem;
      height: 35em;
      justify-content: space-around;
      flex-direction: row;
    }
  }
}

</style>
