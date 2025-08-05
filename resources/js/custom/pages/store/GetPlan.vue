<template>
    <div class="flex flex-col md:flex-row bg-white rounded shadow w-full h-full max-w-screen-lg mx-auto" v-if="plan">
        <div class="w-1/2">
            <img
                v-if="plan.image_plans"
                :src="`http://127.0.0.1:8000${plan.image_plans}`"
                alt="Plan Image"
                class="object-cover w-full h-full rounded-t md:rounded-t-none md:rounded-l"
            />
        </div>

        <!-- Details Section -->
        <div class="w-1/2 p-6 flex flex-col gap-5 ">
            <div>
                <h2 class="text-2xl font-bold text-gray-700 mb-4 truncate">{{ plan.title_plans }}</h2>
                <p class="text-gray-600 mb-6 whitespace-pre-wrap break-words">{{ plan.description_plans }}</p>
                <p class="text-lg font-bold text-yellow-500">{{ plan.price_plans }} $</p>
            </div>
            <div class="flex  items-center space-x-4 mt-4">
                <button
                    class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                    @click="setPayment(plan.id)"
                >
                    Acheter
                </button>
                <a
                    :href="`http://127.0.0.1:8000${plan.file_plans}`"
                    download
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700"
                >
                    Télécharger
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>

import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const plan = ref(null)

onMounted(async () => {
    const id = route.params.id
    try {
        const token = localStorage.getItem('token'); // où tu stockes ton token
        const response = await fetch(`/api/plans/${id}`,{
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}` }
    })
        if (!response.ok) throw new Error("HTTP error " + response.status)
        const data = await response.json()
        plan.value = data.plan
    } catch (error) {
        console.error('Error fetching plan details:', error)
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
