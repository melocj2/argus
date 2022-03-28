
import VueCharts  from '../.././node_modules/vue-chartjs';



export default {
  extends: VueCharts.Line,
  mounted() {

    // Overwriting base render method with actual data.
    VueCharts.Line.methods.renderChart(`<canvas width="500" height="500"></canvas>`, {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      canvas: `<canvas id="myChart" ref="canvas" width="500" height="500" ></canvas>`,
      datasets: [
        {
          label: 'GitHub Commits',
          backgroundColor: '#f87979',
          data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11],
          canvas: `<canvas width="500" height="500" ></canvas>`,
        }
      ]
    }, {responsive: true, maintainAspectRatio: false, canvas: `<canvas id="myChart" ref="canvas" width="500" height="500"></canvas>`})
  }
}