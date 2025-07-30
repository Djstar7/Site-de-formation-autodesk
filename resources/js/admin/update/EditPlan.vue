<template>
    <div class="max-w-xl mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-yellow-500 mb-6">Ajouter un Plan</h2>
        <form @submit.prevent="submitForm" class="space-y-5">
            <div>
                <label class="block text-white mb-1" for="title_plans">Titre du plan</label>
                <input
                    v-model="form.title_plans"
                    id="title_plans"
                    type="text"
                    class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    required
                />
            </div>
            <div>
                <label class="block text-white mb-1" for="description_plans">Description</label>
                <textarea
                    v-model="form.description_plans"
                    id="description_plans"
                    class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    rows="4"
                    required
                ></textarea>
            </div>
            <div>
                <label class="block text-white mb-1" for="price_plans">Prix (€)</label>
                <input
                    v-model="form.price_plans"
                    id="price_plans"
                    type="number"
                    step="0.01"
                    class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    required
                />
            </div>
            <div>
                <label class="block text-white mb-1" for="image_plans">Image</label>
                <input
                    @change="handleImage"
                    id="image_plans"
                    type="file"
                    accept="image/*"
                    class="w-full text-white"
                />
            </div>
            <div>
                <label class="block text-white mb-1" for="file_plans">Fichier du plan</label>
                <input
                    @change="handleFile"
                    id="file_plans"
                    type="file"
                    class="w-full text-white"
                />
            </div>
            <button
                type="submit"
                class="w-full py-2 px-4 bg-yellow-500 text-gray-800 font-semibold rounded hover:bg-yellow-400 transition"
            >
                Ajouter
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
    title_plans: '',
    description_plans: '',
    price_plans: '',
    image_plans: null,
    file_plans: null,
})

function handleImage(e) {
    form.value.image_plans = e.target.files[0]
}

function handleFile(e) {
    form.value.file_plans = e.target.files[0]
}

function submitForm() {
    const formData = new FormData()
    formData.append('title_plans', form.value.title_plans)
    formData.append('description_plans', form.value.description_plans)
    formData.append('price_plans', form.value.price_plans)
    if (form.value.image_plans) {
        formData.append('image_plans', form.value.image_plans)
    }
    if (form.value.file_plans) {
        formData.append('file_plans', form.value.file_plans)
    }
    // Remplacez l'URL ci-dessous par celle de votre backend
    fetch('/api/plans', {
        method: 'POST',
        body: formData,
    })
        .then(res => res.json())
        .then(data => {
            // Gérer la réponse ici (afficher un message, vider le formulaire, etc.)
            alert('Plan ajouté avec succès !')
            form.value = {
                title_plans: '',
                description_plans: '',
                price_plans: '',
                image_plans: null,
                file_plans: null,
            }
        })
        .catch(err => {
            alert('Erreur lors de l\'ajout du plan.')
        })
}
</script>