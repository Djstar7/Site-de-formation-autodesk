<template>
    <div class="min-h-screen bg-white rounded-xl shadow-lg p-8">
        <!-- En-tête -->
        <div class="flex flex-col items-center mb-8">
            <h1 class="text-4xl font-extrabold text-yellow-600 mb-2 drop-shadow">Liste des formations</h1>
            <p class="text-lg text-gray-600">Voici la liste de toutes les formations de notre application.</p>
            <button
                @click="showAddModal = true"
                class="mt-4 px-6 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 transition"
            >
                + Ajouter une formation
            </button>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-2">
                <thead>
                    <tr class="bg-yellow-500 text-white rounded-t-lg">
                        <th class="px-6 py-3 text-left">Titre</th>
                        <th class="px-6 py-3 text-left">Description</th>
                        <th class="px-6 py-3 text-left">Logiciel</th>
                        <th class="px-6 py-3 text-left">Prix</th>
                        <th class="px-6 py-3 text-left">Vidéo</th>
                        <th class="px-6 py-3 text-center rounded-r-lg">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="training in trainings"
                        :key="training.id"
                        class="bg-yellow-50 hover:bg-yellow-100 transition-colors duration-200 shadow rounded-lg"
                    >
                        <td class="px-6 py-3">{{ training.title_trainings }}</td>
                        <td class="px-6 py-3">{{ training.description_trainings }}</td>
                        <td class="px-6 py-3">{{ training.software_trainings }}</td>
                        <td class="px-6 py-3">{{ training.price_trainings }}</td>
                        <td class="px-6 py-3">{{ training.video_trainings }}</td>
                        <td class="px-6 py-3 flex gap-2 justify-center items-center">
                            <button
                                @click="editTraining(training)"
                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                                title="Modifier"
                            >
                                ✏️
                            </button>
                            <button
                                @click="deleteTraining(training.id)"
                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                                title="Supprimer"
                            >
                                🗑️
                            </button>
                        </td>
                    </tr>
                    <tr v-if="trainings.length === 0">
                        <td colspan="6" class="text-center py-6 text-gray-400">Aucune formation trouvée.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajouter/Modifier -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-yellow-600">
                {{ showAddModal ? 'Ajouter une formation' : 'Modifier une formation' }}
            </h2>
            <form @submit.prevent="showAddModal ? addTraining() : updateTraining()">
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Titre</label>
                    <input v-model="form.title_trainings" type="text" class="w-full border rounded px-3 py-2"  />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Description</label>
                    <textarea v-model="form.description_trainings" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Logiciel</label>
                    <input v-model="form.software_trainings" type="text" class="w-full border rounded px-3 py-2"  />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Prix</label>
                    <input v-model="form.price_trainings" type="number" class="w-full border rounded px-3 py-2"  />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Vidéo</label>
                    <input @change="handleVideo" type="file" class="w-full border rounded px-3 py-2"  />
                    <p v-if="showEditModal">Fichier actuelle: {{ form.video_trainings }}</p>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        {{ showAddModal ? 'Ajouter' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Supprimer -->
    <show-delete-modal
        :showDeleteModal="showDeleteModal"
        :pathUrl="pathName"
        @closeModalDelete="handleDeleteConfirmed"
    />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ShowDeleteModal from '../components/ShowDeleteModal.vue'

const pathName = ref('')
const trainings = ref([])
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const form = ref({
    title_trainings: '',
    description_trainings: '',
    software_trainings: 'AutoCAD',
    price_trainings: '',
    video_trainings: '',
    id: null
})
let trainingToDelete = null


const handleVideo = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.value.video_trainings = URL.createObjectURL(file)
    }
}

onMounted(fetchTrainings)
const token = localStorage.getItem('token')
async function fetchTrainings() {
    try {
        const res = await fetch('/api/trainings', {
            headers: { 
                'Content-Type': 'application/json', 
                'authorization': `Bearer ${token}`
            },
        })
        if (!res.ok) throw new Error(res.statusText)
        trainings.value = await res.json()
    } catch (err) {
        console.error('Fetch trainings failed:', err)
    }
}

function closeModal() {
    showAddModal.value = false
    showEditModal.value = false
    form.value = {
        title_trainings: '',
        description_trainings: '',
        software_trainings: 'AutoCAD',
        price_trainings: '',
        video_trainings: '',
        id: null
    }
}

function editTraining(training) {
    form.value = {
        title_trainings: training.title_trainings,
        description_trainings: training.description_trainings,
        software_trainings: training.software_trainings,
        price_trainings: training.price_trainings,
        video_trainings: training.video_trainings,
        id: training.id
    }
    showEditModal.value = true
}

async function addTraining() {
    try {
        const res = await fetch('/api/admin/trainings/store', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                title_trainings: form.value.title_trainings,
                description_trainings: form.value.description_trainings,
                software_trainings: form.value.software_trainings,
                price_trainings: form.value.price_trainings,
                video_trainings: form.value.video_trainings
            })
        })
        if (!res.ok) throw new Error('Erreur lors de l\'ajout')
        const newTraining = await res.json()
        trainings.value.push(newTraining)
        closeModal()
    } catch (err) {
        alert('Erreur lors de l\'ajout')
    }
}

async function updateTraining() {
    try {
        const res = await fetch(`/api/admin/trainings/update/${form.value.id}`, {
            method: 'PUT',
            headers: { 
                'Content-Type': 'application/json', 
                'authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                title_trainings: form.value.title_trainings,
                description_trainings: form.value.description_trainings,
                software_trainings: form.value.software_trainings,
                price_trainings: form.value.price_trainings,
                video_trainings: form.value.video_trainings
            })
        })
        if (!res.ok) throw new Error('Erreur lors de la modification')
        const updatedTraining = await res.json()
        const index = trainings.value.findIndex(t => t.id === updatedTraining.id)
        if (index !== -1) {
            trainings.value[index] = updatedTraining
        }
        closeModal()
    } catch (err) {
        alert('Erreur lors de la modification')
    }
}

function deleteTraining(id) {
    showDeleteModal.value = true
    pathName.value = `/api/trainings/destroy/${id}`
    trainingToDelete = id
}

function handleDeleteConfirmed() {
    if (trainingToDelete !== null) {
        trainings.value = trainings.value.filter(training => training.id !== trainingToDelete)
        trainingToDelete = null
    }
    showDeleteModal.value = false
}
</script>
