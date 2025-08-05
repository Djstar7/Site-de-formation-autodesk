<template>
    <div class="min-h-screen bg-white rounded-xl shadow-lg p-8">

        <!-- En-tête -->
        <div class="flex flex-col items-center mb-8">
        <h1 class="text-4xl font-extrabold text-yellow-600 mb-2 drop-shadow">Liste des plans</h1>
        <p class="text-lg text-gray-600">Voici la liste de tous les plans de notre application.</p>
        <button
            @click="showAddModal = true"
            class="mt-4 px-6 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 transition"
        >
            + Ajouter un plan
        </button>
        </div>

        <!-- Table Wrapper -->
        <div class="overflow-y-auto  max-h-[70vh]">
            <table class="w-full table-fixed border-separate border-spacing-y-2">
                <thead>
                <tr class="bg-yellow-500 text-white">
                    <th class="w-[15%] px-4 py-3 text-left">Titre</th>
                    <th class="w-[30%] px-4 py-3 text-left">Description</th>
                    <th class="w-[10%] px-4 py-3 text-left">Prix</th>
                    <th class="w-[15%] px-4 py-3 text-left">Image</th>
                    <th class="w-[15%] px-4 py-3 text-left">Fichier</th>
                    <th class="w-[15%] px-4 py-3 text-center">Actions</th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="plan in plans"
                    :key="plan.id"
                    class="bg-yellow-50 hover:bg-yellow-100 transition-colors duration-200 shadow rounded-lg "
                >
                    <td class="px-4 py-3 truncate">{{ plan.title_plans }}</td>
                    <td class="px-4 py-3 truncate">{{ plan.description_plans && plan.description_plans.length > 50 ? plan.description_plans.slice(0, 50) + '...' : plan.description_plans }}</td>
                    <td class="px-4 py-3">{{ plan.price_plans }}</td>
                    <td class="px-4 py-3 truncate">{{ plan.image_plans }}</td>
                    <td class="px-4 py-3 truncate">{{ plan.file_plans }}</td>
                    <td class="px-4 py-3 flex gap-2 justify-center items-center">
                    <button
                        @click="editPlan(plan)"
                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                        title="Modifier"
                    >
                        ✏️
                    </button>
                    <button
                        @click="deletePlan(plan.id)"
                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                        title="Supprimer"
                    >
                        🗑️
                    </button>
                    </td>
                </tr>

                <tr v-if="plans.length === 0">
                    <td colspan="6" class="text-center py-6 text-gray-400">Aucun plan trouvé.</td>
                </tr>
                </tbody>
            </table>
        </div>




        <!-- Modal Ajouter/Modifier -->
        <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-lg">
                <h2 class="text-2xl font-bold mb-4 text-yellow-600">
                    {{ showAddModal ? 'Ajouter un plan' : 'Modifier un plan' }}
                </h2>
                <form @submit.prevent="showAddModal ? addPlan() : updatePlan()">
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Titre</label>
                        <input v-model="form.title" type="text" class="w-full border rounded px-3 py-2"  />
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Description</label>
                        <textarea v-model="form.description" class="w-full border rounded px-3 py-2 "></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Prix</label>
                        <input v-model="form.price" type="number" class="w-full border rounded px-3 py-2"  />
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Image</label>
                        <input @change="handleImage" type="file" accept="image/*" class="w-full border rounded px-3 py-2"  />
                        <div v-if="showEditModal" class="mt-2">Chemin actuelle: {{ form.image }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-1 font-semibold">Fichier</label>
                        <input @change="handleFile" type="file" class="w-full border rounded px-3 py-2"  />
                        <div v-if="showEditModal" class="mt-2">Chemin actuelle: {{ form.file }}</div>
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
    </div>

    <!-- Modal Supprimer -->
    <show-delete-modal
        :showDeleteModal="showDeleteModal"
        :pathUrl="pathName"
        :subject = "title"
        @closeModalDelete="handleDeleteConfirmed"
    />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ShowDeleteModal from '../components/ShowDeleteModal.vue'

const pathName = ref('')
const plans = ref([])
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const form = ref({
    title_plans: '',
    description_plans: '',
    price_plans: '',
    image_plans: null,
    file_plans: null,
    id: null
})
let planToDelete = null
const title = ref('Plan')

const handleImage = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.value.image = file
    }
}

const handleFile = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.value.file = file
    }
}

onMounted(fetchPlans)
const token = localStorage.getItem('token')
async function fetchPlans() {
    try {
        const res = await fetch('/api/plans', {
            headers: {
                'Content-Type': 'application/json',
                'authorization': `Bearer ${token}`
            }
        })
        if (!res.ok) throw new Error(res.statusText)
        plans.value = await res.json()
    } catch (err) {
        console.error('Fetch plans failed:', err)
    }
}

function closeModal() {
    showAddModal.value = false
    showEditModal.value = false
    form.value = {
        title: '',
        description: '',
        price: '',
        image: null,
        file: null,
        id: null
    }
}

function editPlan(plan) {
    form.value = {
        title: plan.title_plans,
        description: plan.description_plans,
        price: plan.price_plans,
        image: plan.image_plans,
        file: plan.file_plans,
        id: plan.id
    }
    showEditModal.value = true
}


async function addPlan() {
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('description', form.value.description);
    formData.append('price', form.value.price);
    formData.append('image', form.value.image);
    formData.append('file', form.value.file);
    if (form.value.image instanceof File) {
        formData.append('image', form.value.image);
    }
    if (form.value.file instanceof File) {
        formData.append('file', form.value.file);
    }


    try {
        const res = await fetch('/api/admin/plans/store', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: formData
        });

        const contentType = res.headers.get('content-type');

        if (contentType && contentType.includes('application/json')) {
            const data = await res.json();
            console.log('✅ Plan ajoutée :', data);
        } else {
            const text = await res.text();
            console.error('❌ Erreur lors de l\'ajout (non JSON):', text);
        }

        closeModal();
    } catch (err) {
        console.error('❌ Erreur lors de l\'ajout :', err);
    }
}

async function updatePlan() {
    const formData = new FormData();
    formData.append('_method', 'PUT'); 
    formData.append('title', form.value.title);
    formData.append('description', form.value.description);
    formData.append('price', form.value.price);
    formData.append('image', form.value.image);
    formData.append('file', form.value.file);
    if (form.value.image instanceof File) {
        formData.append('image', form.value.image);
    }
    if (form.value.file instanceof File) {
        formData.append('file', form.value.file);
    }

    try {
        const res = await fetch(`/api/admin/plans/update/${form.value.id}`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'authorization': `Bearer ${token}`
            },
            body: formData
        })
        if (!res.ok) throw new Error('Erreur lors de la modification')
        const updatedPlan = await res.json()
        const index = plans.value.findIndex(t => t.id === updatedPlan.id)
        if (index !== -1) {
            plans.value[index] = updatedPlan
        }
        closeModal()
    } catch (err) {
        console.log('Erreur lors de la modification:', err)
    }
}

function deletePlan(id) {
    showDeleteModal.value = true
    pathName.value = `/api/admin/plans/destroy/${id}`
    planToDelete = id
}

function handleDeleteConfirmed() {
    if (planToDelete !== null) {
        plans.value = plans.value.filter(plan => plan.id !== planToDelete)
        planToDelete = null
    }
    showDeleteModal.value = false
}
</script>
