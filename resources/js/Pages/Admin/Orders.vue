<template>
    <div class=" bg-white rounded-lg shadow-md">
        <table class="min-w-full border-collapse border border-gray-800">
            <thead class="bg-yellow-500 text-white">
                <tr>
                    <th class="px-4 py-2 border border-gray-800 text-center">ID</th>
                    <th class="px-4 py-2 border border-gray-800 text-center">Prix Total</th>
                    <th class="px-4 py-2 border border-gray-800 text-center">Statut</th>
                    <th class="px-4 py-2 border border-gray-800 text-center">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="order in orders"
                    :key="order.id"
                    class="hover:bg-yellow-200 transition-colors"
                >
                    <td class="px-4 py-2 border border-gray-800 text-center">{{ order.id }}</td>
                    <td class="px-4 py-2 border border-gray-800 text-center">{{ order.total_price_orders }}</td>
                    <td class="px-4 py-2 border border-gray-800 text-center">{{ order.status_orders }}</td>
                    <td class="px-4 py-2 border border-gray-800 text-center">{{ new Date(order.created_at).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const orders = ref([])

onMounted(async () => {
  try {
    const res = await fetch('/api/orders')
    if (!res.ok) throw new Error(res.statusText)
    orders.value = await res.json()
  } catch (err) {
    console.error('Fetch orders failed:'+err)
  }
})
</script>

