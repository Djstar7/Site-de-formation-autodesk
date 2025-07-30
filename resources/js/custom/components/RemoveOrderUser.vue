<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
    >
        <div class="bg-white rounded-lg p-6 shadow-lg w-96">
            <h2 class="text-lg font-medium text-gray-800 mb-2">
                Annulation de la commande
            </h2>
            <p class="text-sm text-gray-600 mb-4">
                Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.
            </p>
            <div class="flex justify-end gap-3">
                <button
                    class="px-4 py-2 bg-white border border-gray-800 text-gray-800 rounded-md hover:bg-gray-100"
                    @click="closeModal"
                >
                    Annuler
                </button>
                <button
                    class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600"
                    @click="confirmCancel"
                >
                    Confirmer
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    show: Boolean,
    orderId: Number
})

const emit = defineEmits(['close', 'order-deleted'])

const closeModal = () => {
    emit('close')
}

const confirmCancel = async () => {
    const token = localStorage.getItem('token')

    try {
        const response = await fetch('/api/orders/destroy', {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: props.orderId })
        })

        const data = await response.json()

        if (response.ok) {
            emit('order-deleted', props.orderId)
            closeModal()
        } else {
            console.error(data)
            alert('Échec de l\'annulation : ' + (data.message || 'Erreur inconnue'))
        }
    } catch (err) {
        console.error(err)
        alert('Une erreur est survenue lors de l\'annulation')
    }
}
</script>
