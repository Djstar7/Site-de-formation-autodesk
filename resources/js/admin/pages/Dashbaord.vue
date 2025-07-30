<template>
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-yellow-500 rounded-lg p-6 shadow flex flex-col items-center border border-yellow-500">
            <span class="text-gray-800 text-3xl material-icons mb-2">school</span>
            <div class="text-2xl font-bold text-white">128</div>
            <div class="text-white">Cours disponibles</div>
        </div>
        <div class="bg-yellow-500 rounded-lg p-6 shadow flex flex-col items-center border border-yellow-500">
            <span class="text-gray-800 text-3xl material-icons mb-2">home</span>
            <div class="text-2xl font-bold text-white">54</div>
            <div class="text-white">Plans de maison</div>
        </div>
        <div class="bg-yellow-500 rounded-lg p-6 shadow flex flex-col items-center border border-yellow-500">
            <span class="text-gray-800 text-3xl material-icons mb-2">people</span>
            <div class="text-2xl font-bold text-white">320</div>
            <div class="text-white">Utilisateurs actifs</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Evolution des commandes -->
        <div class="bg-white rounded-lg p-6 shadow mb-8 border border-yellow-500 h-full">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Évolution des commandes</h2>
            <div class="flex-1 flex items-center">
            <LineChart :chart-data="ordersChartData" :chart-options="ordersChartOptions" class="w-full" />
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg p-6 shadow border border-yellow-500 h-full">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Dernières activités</h2>
            <ul class="divide-y divide-yellow-500 flex-1">
            <li v-for="(activity, i) in recentActivities" :key="i" class="py-2 flex items-center">
                <span class="material-icons text-yellow-500 mr-3">{{ activity.icon }}</span>
                <span class="text-gray-800">{{ activity.text }}</span>
                <span class="ml-auto text-xs text-gray-800">{{ activity.time }}</span>
            </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, defineAsyncComponent } from 'vue'

const LineChart = defineAsyncComponent(() => import('../components/LineChart.vue'))

const ordersChartData = ref({
    labels: [],
    datasets: [
        {
            label: 'Commandes',
            backgroundColor: 'rgba(253, 224, 71, 0.2)', // yellow-500 with opacity
            borderColor: '#fde047', // yellow-500
            data: [],
            fill: true,
            tension: 0.4,
        }
    ]
})

const ordersChartOptions = ref({
    responsive: true,
    plugins: {
        legend: { display: false }
    },
    scales: {
        x: { ticks: { color: '#1f2937' }, grid: { color: '#fde047' } }, // gray-800, yellow-500
        y: { ticks: { color: '#1f2937' }, grid: { color: '#fde047' } }
    }
})

onMounted(async () => {
    try {
        const res = await fetch('/api/orders')
        if (!res.ok) throw new Error(res.statusText)
        const orders = await res.json()
        const ordersByDate = {}
        orders.forEach(order => {
            const date = new Date(order.created_at).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' })
            ordersByDate[date] = (ordersByDate[date] || 0) + 1
        })
        const labels = Object.keys(ordersByDate).sort((a, b) => {
            const [da, ma, ya] = a.split('/')
            const [db, mb, yb] = b.split('/')
            return new Date(`${ya}-${ma}-${da}`) - new Date(`${yb}-${mb}-${db}`)
        })
        const data = labels.map(date => Math.floor(ordersByDate[date]))
        ordersChartData.value.labels = labels
        ordersChartData.value.datasets[0].data = data
    } catch (err) {
        console.error('Erreur lors de la récupération des commandes :', err)
    }
})

const recentActivities = ref([
    { icon: 'school', text: 'Nouveau cours ajouté: AutoCAD Avancé', time: 'il y a 2h' },
    { icon: 'person_add', text: 'Nouvel utilisateur inscrit', time: 'il y a 3h' },
    { icon: 'home', text: 'Nouveau plan de maison publié', time: 'il y a 5h' },
    { icon: 'update', text: 'Mise à jour du cours: Revit Débutant', time: 'il y a 1j' },
])
</script>
