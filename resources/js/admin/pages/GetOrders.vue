<template>
  <div class="min-h-screen  bg-white rounded-xl shadow-lg p-8">
    <!-- Titre -->
    <div class="flex flex-col items-center mb-8">
        <h1 class="text-4xl font-extrabold text-yellow-600 mb-2 drop-shadow">Historiques des commandes</h1>
        <p class="text-lg text-gray-600">Retrouvez ci-dessous la liste de toutes les commandes passées sur la plateforme.</p>
    </div>
    <!-- Tableau -->
    <div class="overflow-y-auto  max-h-[70vh] rounded-lg border border-gray-200">
      <table class="min-w-full table-auto border-collapse">
        <thead class="bg-yellow-500 text-white uppercase text-sm tracking-wider">
          <tr>
            <th class="px-6 py-4 border border-gray-800 text-center">ID</th>
            <th class="px-6 py-4 border border-gray-800 text-center">Prix total</th>
            <th class="px-6 py-4 border border-gray-800 text-center">Statut</th>
            <th class="px-6 py-4 border border-gray-800 text-center">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="order in orders"
            :key="order.id"
            class="hover:bg-yellow-100 text-gray-700 text-sm transition-colors"
          >
            <td class="px-6 py-3 border border-gray-800 text-center font-medium">
              {{ order.id }}
            </td>
            <td class="px-6 py-3 border border-gray-800 text-center">
              {{ order.total_price_orders }} FCFA
            </td>
            <td class="px-6 py-3 border border-gray-800 text-center">
              <span
                :class="[
                  'px-3 py-1 rounded-full text-xs font-semibold',
                  order.status_orders === 'completed'
                    ? 'bg-green-100 text-green-700'
                    : order.status_orders === 'pending'
                    ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-red-100 text-red-700'
                ]"
              >
                {{ order.status_orders }}
              </span>
            </td>
            <td class="px-6 py-3 border border-gray-800 text-center">
              {{ formatDate(order.created_at) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const orders = ref([])

const formatDate = (dateStr) =>
  new Date(dateStr).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })

onMounted(async () => {
  try {
    const res = await fetch('/api/orders')
    if (!res.ok) throw new Error(res.statusText)
    orders.value = await res.json()
    console.log('Orders fetched successfully:', orders.value)
  } catch (err) {
    console.error('Erreur lors de la récupération des commandes :', err)
  }
})
</script>
