<template>
    <div class=" bg-white rounded-lg shadow-md">
        <table class="min-w-full border-collapse border border-gray-800">
            <thead class="bg-yellow-500 text-white">
                <tr>
                    <th class="px-4 py-2 border border-gray-800 text-center">ID</th>
                    <th class="px-4 py-2 border border-gray-800 text-center">Prix Total</th>
                    <th class="px-4 py-2 border border-gray-800 text-center">Statut</th>
                    <th class="px-4 py-2 border border-gray-800 text-center">Date</th>
                    <th class="px-4 py-2 border border-gray-800 text-center">Payer</th>
                    <th class="px-4 py-2 border border-gray-800 text-center">Annuler</th>
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
                    <td class="px-4 py-2 border border-gray-800 text-center">{{ order.choix_orders }}</td>
                    <td class="px-4 py-2 border border-gray-800 text-center">
                        {{ new Date(order.created_at).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' }) }}
                    </td>
                    <td class="px-4 py-2 border border-gray-800 text-center">
                        <button
                            @click="handlePayment(order.id)"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            title="Payer"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </td>
                    <td class="px-4 py-2 border border-gray-800 text-center">
                        <button
                            @click="getCancelOrder(order.id)"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                            title="Annuler"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- ✅ La modale s'ouvre uniquement quand showSuccess est true -->
        <modal-remove-order-users
            :show="showSuccess"
            :orderId="selectedOrderId"
            @close="showSuccess = false"
            @order-deleted="handleOrderDeleted"
        />

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import ModalRemoveOrderUsers from './models/ModalRemoveOrderUsers.vue'

const router = useRouter()
const orders = ref([])

const token = localStorage.getItem('token')
const userId = localStorage.getItem('id')

const showSuccess = ref(false)
const selectedOrderId = ref(null)

// 🧠 Redirection vers la page de paiement
function handlePayment(orderId) {
    orders.value = orders.value.filter(order => order.id !== orderId)
    router.push({ name: 'pay', params: { prix:  orders.value.total_price_orders} })
}

// 🧠 Affiche la modale de confirmation
function getCancelOrder(orderId) {
    selectedOrderId.value = orderId
    showSuccess.value = true
}



// ✅ Supprime localement la commande après suppression confirmée
function handleOrderDeleted(orderId) {
    orders.value = orders.value.filter(order => order.id !== orderId)
    showSuccess.value = false
    selectedOrderId.value = null
}

// 📥 Charger les commandes de l'utilisateur
onMounted(async () => {
    try {
        const res = await fetch(`/api/orders/${userId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        if (!res.ok) throw new Error(res.statusText)
        const data = await res.json()
        orders.value = data.order
    } catch (err) {
        console.error(`Fetch orders failed: ${err}`)
    }
})


</script>
