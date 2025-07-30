<template>
    <div class="min-h-screen flex flex-col">
        <header class="bg-yellow-500 py-6">
            <div class="container mx-auto text-center">
                <h1 class="text-4xl font-bold text-white">Plans</h1>
                <p class="text-lg mt-2 text-gray-800">Découvrez nos plans de maisons adaptés à vos besoins et à votre style de vie.</p>
            </div>
        </header>
        <main class="flex-grow container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="plan in plans" :key="plan.id" class="border p-4 rounded shadow bg-white flex flex-col">
                    <div>
                        <img
                            v-if="plan.image_plans"
                            :src="plan.image_plans"
                            alt="Plan Image"
                            class="w-full h-48 object-cover rounded mb-4"
                        />
                        <h3 class="text-xl font-semibold mb-2 text-gray-600">{{ plan.title_plans }}</h3>
                        <p class="text-lg font-bold mb-4 text-yellow-500">{{ plan.price_plans }} $</p>
                    </div>
                    <div class="flex mt-auto space-x-2 overflow-hidden w-full flex-col sm:flex-row">
                        <button
                            class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 flex-1 truncate w-full text-sm md:text-base mb-2 sm:mb-0"
                            @click="ordersPlan(plan.id)"
                        >
                            Commander
                        </button>
                        <button
                            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex-1 truncate w-full text-sm md:text-base mb-2 sm:mb-0"
                            @click="viewDetails(plan.id)"
                        >
                            Détails
                        </button>
                    </div>
                </div>
                <modal-get-orders-user :show="showSuccess" :description="desc" @close="showSuccess = false"/>
            </div>
        </main>
    </div>
</template>
<script setup>
import { ref, onMounted, onBeforeMount } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import ModalGetOrdersUser from '../components/GetOrdersUsers.vue'

const router = useRouter()
const route = useRoute()

const plans = ref([])
const showSuccess = ref(false)
const desc = 'Plan commander avec succes. \nConsulter vos commandes sur la page commande'




const token = localStorage.getItem('token')
const userId = localStorage.getItem('id')

const ordersPlan = async (id) => {
    try {
        const plan = plans.value.find(t => t.id === id)
        if (!plan) {
            throw new Error('plan not found')
        }

        const res = await fetch('/api/orders/store', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                userId: userId,
                planId: plan.id,
                totalPriceOrders: plan.price_plans,
                choix: 'Plan'
            })
        })

        if (!res.ok) {
            throw new Error(`Failed to place order: ${res.statusText}`)
        }

        const data = await res.json()
        showSuccess.value = true
        console.log('Order placed successfully:', data)
    } catch (err) {
        console.error('Error placing order:', err)
    }
}


function viewDetails(id) {
    router.push({ name: 'plan', params: { id } })
}



const error = ref(null)
onBeforeMount(() => {
    const token = localStorage.getItem('token')
    if (!token) {
        // Redirige vers login et retient la page demandée
        return router.replace({
            path: '/login',
            query: { redirect: route.fullPath }
        })
    }
})

onMounted(fetchPlans)

async function fetchPlans() {
  const token = localStorage.getItem('token')

  if (!token) {
    error.value = "Vous devez être connecté pour voir les plans."
    return
  }

  try {
    const res = await fetch('/api/plans', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    })

    if (res.status === 401) {
      // token invalide ou expiré
      error.value = 'Non autorisé – veuillez vous reconnecter.'
      // ici, on peut router vers /login si on veut
      return
    }

    if (!res.ok) {
      throw new Error(`Erreur réseau ${res.status}: ${res.statusText}`)
    }

    // si on arrive ici, tout s'est bien passé
    const json = await res.json()
    // selon votre API, soit c'est directement un array, soit { data: [...] }
    plans.value = Array.isArray(json) ? json : json.data || []

  } catch (err) {
    error.value = err.message
    console.error('Fetch plans failed:', err)
  }
}
</script>
