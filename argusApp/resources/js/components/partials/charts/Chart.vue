<template>
  <div class="chartCompContainer" v-if="chartData">

    <GChart class="chart"
      :type="chartType"
      :options="chartData.options"
      :data="chartData.chartData"
    />
  </div>
</template>

<script>
import { GChart } from "vue-google-charts";
export default {
  props: ["chartData", "chartType"],
  name: "App",
  components: {
    GChart
  }
};
</script>

<style lang="scss">

@import "../../../.././sass/variables/breakpoints.scss";
@import "../../../.././sass/variables/fonts.scss";
@import "../../../.././sass/variables/colors.scss";

  .chartCompContainer {
    z-index: 10;
    .chart{
      z-index: 10;
       * {
        font-family: $font !important;
        font-size: 12px !important;
        font-style: normal !important;
      }
    }
  }

  @media (min-width: $tablet-break) {
    .chartCompContainer {
      z-index: 10;
      .chart{
        z-index: 10;
        * {
          font-family: $font !important;
          font-size: 15px !important;
          font-style: normal !important;
        }
      }
    }
  }

   @media (min-width: $desktop-break) {
    .chartCompContainer {
      z-index: 10;
      .chart{
        z-index: 10;
        * {
          font-family: $font !important;
          font-size: 17px !important;
          font-style: normal !important;
        }
      }
    }
  }
</style>