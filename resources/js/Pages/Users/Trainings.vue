<template>
    <header class="bg-yellow-500">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold text-white ">Formations</h1>
            <p class="text-lg mt-2 text-gray-800">Bienvenue sur la page des formations. Découvrez nos offres !</p>
        </div>
    </header>
    <div class="container mt-8 m-3">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="training in trainings" :key="training.id" :class="['border p-4 opacity-100 rounded shadow bg-white w-full h-full flex flex-col justify-between', 'hover:shadow-lg transition duration-300 ease-in-out'? active = true: active = false]" >
                <div>
                    <video
                        :src="`http://127.0.0.1:8000/${training.video_trainings}`"
                        muted
                        :autoplay='active'
                        loop
                        :controls="false"
                        :playbackRate="10"
                        @timeupdate="restartAfterTrentySeconds"
                        class="object-cover"
                    ></video>
                    <h3 class="text-xl font-semibold mb-2 text-gray-600">{{ training.title_trainings }}</h3>
                    <p class="text-lg font-bold mb-4 text-yellow-500">{{ training.price_trainings }} $</p>
                </div>
                <div class="flex flex-col sm:flex-row justify-between mt-auto space-y-2 sm:space-y-0 sm:space-x-2">
                    <button
                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full sm:w-auto flex-1 truncate  text-sm md:text-base"
                        @click="ordersTraining(training.id)"
                    >
                        Commander
                    </button>
                    <button
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 w-full sm:w-auto flex-1 truncate text-sm md:text-base"
                        @click="viewDetails(training.id)"
                    >
                        Détails
                    </button>
                </div>
            </div>

            <modal-get-orders-users :show="showSuccess" :description="desc" @close="showSuccess = false"/>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, onBeforeMount } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import ModalGetOrdersUsers from './models/ModalgetOrdersUsers.vue'

const router = useRouter()
const route = useRoute()

const trainings = ref([])
const showSuccess = ref(false)
const active = ref(false)
const desc = 'Formation commander avec succes. \nConsulter vos commandes sur la page commande'


const token = localStorage.getItem('token')
const userId = localStorage.getItem('id')

const ordersTraining = async (id) => {
    try {
        const training = trainings.value.find(t => t.id === id)
        if (!training) {
            throw new Error('Training not found')
        }
        console.log('Training:', training.id+ ' ' + training.price_trainings+' UserId: '+userId)
        // Envoi de la commande
        const res = await fetch('/api/orders/store', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                userId: userId,
                trainingId: training.id,
                totalPriceOrders: training.price_trainings,
                choix: 'Formation'
            })
        })

        if (!res.ok) {
            throw new Error(`Failed to place order: ${res.statusText}`)
        }

        const data = await res.json()
        showSuccess.value = true // Afficher le modal
    } catch (err) {
        console.error('Error placing order:', err)
    }
}

function viewDetails(id) {
    router.push({ name: 'training', params: { id } })
}

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
onMounted(async () => {
    const token = localStorage.getItem('token')
    try {
        const res = await fetch('/api/trainings', {
        headers: {
            'Accept':        'application/json',
            'Authorization': `Bearer ${token}`
        }
    }); // Remplacez l'URL par celle de votre API pour les formations
        if (!res.ok) throw new Error(res.statusText);
        trainings.value = await res.json();
    } catch (err) {
        console.error('Fetch trainings failed:', err);
    }
});


</script>
