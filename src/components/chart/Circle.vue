<script>
import { Doughnut } from 'vue-chartjs'
export default {
  extends: Doughnut,
  props: ['labels', 'data', 'colors'],
  mounted () {
    this.renderChart({
      labels: this.labels,
      datasets: [
        {
          backgroundColor: this.colors,
          data: this.data
        }
      ]
    }, {responsive: true, maintainAspectRatio: false, legend: {display: false}, cutoutPercentage: 85}
    )
  }
}
</script>
