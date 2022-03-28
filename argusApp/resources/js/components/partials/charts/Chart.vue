<template>
  <div>
    <h2>{{chartData.options.title}}</h2>
    <GChart
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
  },
  data() {
    return {
      chartData: this.chartData
    };
  }
};
</script>