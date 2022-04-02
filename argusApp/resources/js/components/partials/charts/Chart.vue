<template>
  <div id="chart" v-if="chartData">

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

  #chart {
    .chart{
       * {
        font-family: $font !important;
        font-size: 20px !important;
        font-style: normal !important;
      }
    }
  }
</style>