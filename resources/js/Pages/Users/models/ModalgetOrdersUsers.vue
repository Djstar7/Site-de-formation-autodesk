<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 shadow-lg w-80">
            <h2 class="text-xl font-semibold text-green-600 mb-4">Succès !</h2>
            <p class="text-gray-700">{{ description }}</p>
            <div class="mt-6 flex justify-center">
                <button class="ml-2 bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 hover:cursor-pointer" @click="getOrdersUsers">Consulter</button>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { watch, computed, ref } from 'vue'
    import { useRouter } from 'vue-router'

    const router = useRouter()

    const props = defineProps({
        show: Boolean,
        description: String,
    })

    const emit = defineEmits(['close'])

    const close = () => {
        emit('close')
    }

    watch(
        () => props.show,
        (newVal) => {
            if (newVal) {
                setTimeout(() => {
                    close()
                }, 3000) // Fermer après 5 secondes
            }
        }
    )
    // Récupère l'ID uniquement quand on est en client
    const userId = ref(null)
    if (typeof window !== 'undefined') {
        userId.value = localStorage.getItem('id')
    }

    // (optionnel) pour construire le path
    const ordersPath = computed(() => `/orders/${userId.value}`)

    const getOrdersUsers = () => {
        router.push(ordersPath.value)
        close()
    }
</script>
