<template>
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-lg">
            <h2 class="text-xl font-bold mb-4 text-red-600">Supprimer l'utilisateur {{ user?.name }}</h2>
            <p class="mb-6">Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
            <div class="flex justify-end gap-2">
                <button type="button" @click="closeModalDelete" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Annuler</button>
                <button @click="deleteUser(pathUrl)" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Supprimer
                </button>
            </div>
            <div>
                <p v-if="message" class="mt-4 text-green-600">{{ message }}</p>
            </div>
        </div>
    </div>
</template>
<script setup>
import useDelete from '../../composables/useDelete'
const emit = defineEmits(['closeModalDelete']);
const props = defineProps({
    showDeleteModal: Boolean,
    pathUrl: String
});

import { ref } from 'vue';

const user = ref(JSON.parse(localStorage.getItem('user')));
const message = ref('');

const closeModalDelete = () => {
    emit('closeModalDelete');
    message.value = '';
}

const deleteUser = async (url) => {
    const { message: deleteMessage } = await useDelete(url);
    message.value = deleteMessage;
    closeModalDelete();
}

</script>
