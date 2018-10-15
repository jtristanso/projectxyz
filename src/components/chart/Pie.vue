<script>
import { Pie } from 'vue-chartjs'
export default {
  extends: Pie,
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
    }, {responsive: true, maintainAspectRatio: false})
  }
}
</script>
