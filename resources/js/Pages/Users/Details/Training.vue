<template>
    <div class="flex flex-col lg:flex-row bg-white h-full  shadow overflow-hidden" :class="!buy ? 'lg:flex-col': ''"  v-if="training">
        <div
            :class="['group relative overflow-hidden', !buy ? 'lg:w-1/1' : 'lg:w-1/2']"
        >

        <!-- La vidéo elle-même -->
            <video
                :src="`http://127.0.0.1:8000/${training.video_trainings}`"
                class="object-contain w-full h-full opacity-100 hover:cursor-pointer"
                :controls="!buy"
                controlslist="nodownload"
            ></video>

            <!-- Overlay noir + icône Play, caché par défaut -->
            <div
                :class="[!buy ? '':'absolute inset-0 bg-black bg-opacity-90 flex items-center justify-center transition-opacity duration-300 pointer-events-none']"
            >
                <!-- SVG du Play : seul lui capte les événements et affiche le pointeur -->
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="white"
                    class="w-16 h-16 pointer-events-auto cursor-pointer"
                    aria-hidden="true"
                    @click="accessTraining"
                >
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </div>
        </div>

        <!-- Details Section -->
        <div :class="['p-6 flex flex-col gap-8', !buy ? 'lg:w-1/1' : 'lg:w-1/2']">
            <div>
                <h2 class="text-2xl font-bold text-gray-700 mb-2">{{ training.title_trainings }}</h2>
                <p class="text-gray-600 mb-4">{{ training.description_trainings }}</p>
                <p class="text-gray-600 mb-4">Logiciel : {{ training.software_trainings }}</p>
                <p class="text-lg font-bold text-yellow-500">{{ training.price_trainings }} $</p>
            </div>
            <div class="mt-4 flex items-center">
                <div v-if="buy">
                    <button
                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                        @click="setPayment"
                    >
                        Payer
                    </button>
                </div>
                <div v-else class="space-x-10">
                    <a
                        :href="`http://127.0.0.1:8000/${training.video_trainings}`"
                        target="_blank"
                        class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700"
                    >
                        Regarder
                    </a>
                    <a
                        :href="`http://127.0.0.1:8000/${training.video_trainings}`"
                        download
                        class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700"
                    >
                        Telecharger
                    </a>
                </div>
            </div>
        </div>
        <modal-set-payment :show="showSuccess" :description="desc" @close="showSuccess = false"/>
    </div>

</template>

<script setup>

import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import ModalSetPayment from '../models/ModalSetPayment.vue'

const route = useRoute()
const router = useRouter()

const training = ref(null)
const buy =ref(true)

const showSuccess = ref(false)
const desc = 'Merci de payer d\'abord.'


function accessTraining(){
    if(buy.value){
        showSuccess.value = true
    }
}
function handlePayment() {
    router.push({ name: 'pay' })
}
onMounted(async () => {
    const id = route.params.id
    try {
        const token = localStorage.getItem('token'); // où tu stockes ton token
        const response = await fetch(`/api/trainings/${id}`, {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        if (!response.ok) throw new Error("HTTP error " + response.status)
        const data = await response.json()
        training.value = data.training
    } catch (error) {
        console.error('Error fetching training details:', error)
    }
})

const setPayment = () => {
    router.push('/pay')
    close()
}

</script>

<style scoped>
/* Add any additional styles if needed */
</style>
