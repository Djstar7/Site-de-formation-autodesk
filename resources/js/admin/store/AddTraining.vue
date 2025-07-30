<template>
    <form @submit.prevent="submitForm" class="max-w-lg mx-auto bg-white p-8 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Ajouter une formation</h2>
        <div class="mb-4">
            <label class="block text-gray-800 mb-2" for="title">Titre de la formation</label>
            <input
                v-model="form.title_trainings"
                id="title"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                required
            />
        </div>
        <div class="mb-4">
            <label class="block text-gray-800 mb-2" for="description">Description</label>
            <textarea
                v-model="form.description_trainings"
                id="description"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                required
            ></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-800 mb-2" for="software">Logiciel</label>
            <select
                v-model="form.software_trainings"
                id="software"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                required
            >
                <option value="AutaCAD">AutaCAD</option>
                <option value="Revit">Revit</option>
                <option value="ArciCAD">ArciCAD</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-800 mb-2" for="price">Prix</label>
            <input
                v-model="form.price_trainings"
                id="price"
                type="number"
                step="0.01"
                min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                required
            />
        </div>
        <div class="mb-6">
            <label class="block text-gray-800 mb-2" for="video">Lien vidéo</label>
            <input
                @change="handleVideo"
                id="video"
                type="file"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                required
            />
        </div>
        <button
            type="submit"
            class="w-full bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-600 transition"
            :disabled="loading"
        >
            {{ loading ? 'Envoi...' : 'Ajouter la formation' }}
        </button>
        <p v-if="error" class="mt-4 text-red-600">{{ error }}</p>
        <p v-if="success" class="mt-4 text-green-600">Formation ajoutée avec succès !</p>
    </form>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const form = ref({
    title_trainings: '',
    description_trainings: '',
    software_trainings: 'AutaCAD',
    price_trainings: '',
    video_trainings: ''
})

const loading = ref(false)
const error = ref('')
const success = ref(false)

function handleVideo(e) {
    form.value.file_plans = e.target.files[0]
}


const submitForm = async () => {
    loading.value = true
    error.value = ''
    success.value = false
    try {
        await axios.post('/api/trainings', form.value)
        success.value = true
        form.value = {
            title_trainings: '',
            description_trainings: '',
            software_trainings: 'AutaCAD',
            price_trainings: '',
            video_trainings: ''
        }
    } catch (e) {
        error.value = "Erreur lors de l'ajout de la formation."
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
form {
    border: 2px solid #1f2937; /* gray-800 */
}
</style>