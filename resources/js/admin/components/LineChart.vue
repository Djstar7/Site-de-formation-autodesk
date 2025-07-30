<template>
  <div>
    <canvas ref="canvas"></canvas>
  </div>
</template>

<script setup>
import { onMounted, watch, ref } from 'vue'
import {
  Chart,
  LineController,
  LineElement,
  PointElement,
  LinearScale,
  Title,
  CategoryScale,
  Filler
} from 'chart.js'

Chart.register(LineController, LineElement, PointElement, LinearScale, Title, CategoryScale, Filler)

const props = defineProps({
  chartData: { type: Object, required: true },
  chartOptions: { type: Object, default: () => ({}) }
})

const canvas = ref(null)
let chartInstance = null

const renderChart = () => {
  if (!canvas.value) return

  if (chartInstance) {
    chartInstance.destroy()
  }

  chartInstance = new Chart(canvas.value.getContext('2d'), {
    type: 'line',
    data: props.chartData,
    options: props.chartOptions
  })
}

onMounted(() => {
  renderChart()
})

watch(
  () => props.chartData,
  () => {
    renderChart()
  },
  { deep: true }
)
</script>

<style scoped>
canvas {
  max-width: 100%;
  background: #f3f4f5;
  border-radius: 0.5rem;
}
</style>
