<template>
  <div class="p-4 space-y-4 bg-gray-50 min-h-[70vh]">
    <h1 class="text-2xl text-center font-bold text-gray-800">Tableau de bord administrateur</h1>

    <!-- Cartes Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-yellow-500 p-4 rounded-md shadow">
        <h2 class="text-lg font-semibold text-center text-gray-800">
          <span class="material-icons text-lg mr-2">people</span> Utilisateurs
        </h2>
        <p class="text-xl text-center font-bold text-white">{{ stats.totals.users }}</p>
      </div>
      <div class="bg-yellow-500 p-4 rounded-md shadow">
        <h2 class="text-lg font-semibold text-center text-gray-800">
          <span class="material-icons text-lg mr-2">school</span> Formations
        </h2>
        <p class="text-xl text-center font-bold text-white">{{ stats.totals.trainings }}</p>
      </div>
      <div class="bg-yellow-500 p-4 rounded-md shadow">
        <h2 class="text-lg font-semibold text-center text-gray-800">
          <span class="material-icons text-lg mr-2">home</span> Plans
        </h2>
        <p class="text-xl text-center font-bold text-white">{{ stats.totals.plans }}</p>
      </div>
      <div class="bg-yellow-500 p-4 rounded-md shadow">
        <h2 class="text-lg font-semibold text-center text-gray-800">
          <span class="material-icons text-lg mr-2">receipt_long</span> Commandes
        </h2>
        <p class="text-xl text-center font-bold text-white">{{ stats.totals.orders }}</p>
      </div>
    </div>

    <!-- Ventes Aujourd’hui / Hier / Variation -->
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-base font-semibold text-gray-800 mb-2">Ventes</h2>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-3 bg-yellow-50 rounded">
          <p class="text-base pb-1 text-gray-600">Aujourd’hui</p>
          <p class="text-xl font-bold text-yellow-500">{{ format(stats.sales.today) }} FCFA</p>
        </div>
        <div class="p-3 bg-yellow-50 rounded">
          <p class="text-base pb-1 text-gray-600">Hier</p>
          <p class="text-xl font-bold text-yellow-500">{{ format(stats.sales.yesterday) }} FCFA</p>
        </div>
        <div
          class="p-3 rounded"
          :class="{
            'bg-green-50 text-green-700': trend === 'up',
            'bg-red-50 text-red-700': trend === 'down',
            'bg-gray-100 text-gray-700': trend === 'stable'
          }">
          <p class="text-base pb-1">Variation</p>
          <p class="text-xl font-bold">{{ trendMessage }} {{ variation }}%</p>
        </div>
      </div>
    </div>

    <!-- Courbe des ventes mensuelles -->
    <div class="text-white rounded-lg shadow">
      <div class="mb-4 flex flex-col sm:flex-row justify-between items-center">
        <div class="bg-yellow-200 text-gray-800 p-3 rounded-lg shadow w-full sm:w-1/2 mr-0 sm:mr-2 text-center mb-3 sm:mb-0">
          <h3 class="text-base font-bold">Mois Précédent</h3>
          <p class="text-2xl font-bold">{{ format(previousMonthSales) }} FCFA</p>
        </div>
        <div class="bg-white text-gray-800 p-3 rounded-lg shadow w-full sm:w-1/2 text-center">
          <h3 class="text-base font-bold">Mois en Cours</h3>
          <p class="text-2xl font-bold text-blue-500">{{ format(currentMonthSales) }} FCFA</p>
        </div>
      </div>

      <div class="bg-white p-4 rounded-lg">
        <canvas id="salesChart"></canvas>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Chart from 'chart.js/auto'

const stats = ref({
  totals: { users: 0, trainings: 0, plans: 0, orders: 0 },
  sales: { today: 0, yesterday: 0 }
})

const variation = ref(0)
const trend = ref('stable')
const trendMessage = ref('')
const salesByDay = ref([])
const currentMonthSales = ref(0)
const previousMonthSales = ref(0)
let chartInstance = null

const format = (value) => Number(value).toLocaleString('fr-FR')

const drawChart = () => {
  const labels = salesByDay.value.map(e => e.date)
  const totals = salesByDay.value.map(e => e.total)

  const ctx = document.getElementById('salesChart')?.getContext('2d')
  if (!ctx) return

  if (chartInstance) chartInstance.destroy()

  chartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Évolution journalière du mois',
        data: totals,
        fill: true,
        borderColor: '#facc15',
        backgroundColor: 'rgba(250, 204, 21, 0.2)',
        tension: 0.4,
        pointBackgroundColor: '#1f2937',
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { labels: { color: '#1f2937' } },
        tooltip: { mode: 'index', intersect: false }
      },
      scales: {
        x: { ticks: { color: '#1f2937' } },
        y: { beginAtZero: true, ticks: { color: '#1f2937' } }
      }
    }
  })
}

const updateVariation = () => {
  const today = stats.value.sales.today
  const yesterday = stats.value.sales.yesterday

  if (yesterday > 0) {
    variation.value = Math.round(((today - yesterday) / yesterday) * 100)
    trend.value = variation.value > 0 ? 'up' : variation.value < 0 ? 'down' : 'stable'
  } else {
    variation.value = today > 0 ? 100 : 0
    trend.value = today > 0 ? 'up' : 'stable'
  }

  trendMessage.value = trend.value === 'up' ? '📈' : trend.value === 'down' ? '📉' : '📊'
}

const fillMissingDays = () => {
  const today = new Date()
  const start = new Date(today.getFullYear(), today.getMonth(), 1)
  const map = new Map(salesByDay.value.map(e => [e.date, e.total]))

  const filled = []
  for (let d = new Date(start); d <= today; d.setDate(d.getDate() + 1)) {
    const date = d.toISOString().split('T')[0]
    filled.push({ date, total: map.get(date) || 0 })
  }

  salesByDay.value = filled
}

const fetchStats = async () => {
  try {
    const res = await fetch('/api/dashboard', { headers: { Accept: 'application/json' } })
    if (!res.ok) throw new Error('Erreur API')
    const result = await res.json()

    stats.value = {
      totals: result.data.totals,
      sales: {
        today: result.data.sales.today,
        yesterday: result.data.sales.yesterday
      }
    }

    salesByDay.value = result.data.sales.salesByDay || []
    currentMonthSales.value = result.data.sales.currentMonthSales || 0
    previousMonthSales.value = result.data.sales.previousMonthSales || 0

    fillMissingDays()
    updateVariation()
    setTimeout(drawChart, 0)
  } catch (err) {
    console.error('Erreur chargement stats:', err)
  }
}

onMounted(fetchStats)
</script>
