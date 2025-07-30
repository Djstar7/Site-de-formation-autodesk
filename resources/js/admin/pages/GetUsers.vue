<template>
    <div class="min-h-screen  bg-white rounded-xl shadow-lg p-8">
        <!-- En-tête -->
        <div class="flex flex-col items-center mb-8">
            <h1 class="text-4xl font-extrabold text-yellow-600 mb-2 drop-shadow">Liste des utilisateurs</h1>
            <p class="text-lg text-gray-600">Voici la liste de tous les utilisateurs de notre application.</p>
            <button
                @click="showAddModal = true"
                class="mt-4 px-6 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 transition"
            >
                + Ajouter un utilisateur
            </button>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-2">
                <thead>
                    <tr class="bg-yellow-500 text-white rounded-t-lg">
                        <th class="px-6 py-3 text-left">Nom</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Rôle</th>
                        <th class="px-6 py-3 text-center rounded-r-lg">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="user in users"
                        :key="user.id"
                        class="bg-yellow-50 hover:bg-yellow-100 transition-colors duration-200 shadow rounded-lg"
                    >
                        <td class="px-6 py-3">{{ user.name_usersone }}</td>
                        <td class="px-6 py-3">{{ user.email_usersone }}</td>
                        <td class="px-6 py-3">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-bold"
                                :class="{
                                    'bg-green-100 text-green-700': user.role_usersone === 'admin',
                                    'bg-blue-100 text-blue-700': user.role_usersone === 'user',
                                    'bg-gray-200 text-gray-700': user.role_usersone !== 'admin' && user.role_usersone !== 'user'
                                }"
                            >
                                {{ user.role_usersone }}
                            </span>
                        </td>
                        <td class="px-6 py-3 flex gap-2 justify-around">
                            <button
                                @click="editUser(user)"
                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                                title="Modifier"
                            >
                                ✏️
                            </button>
                            <button
                                @click="deleteUser(user.id)"
                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                                title="Supprimer"
                            >
                                🗑️
                            </button>
                        </td>
                    </tr>
                    <tr v-if="users.length === 0">
                        <td colspan="4" class="text-center py-6 text-gray-400">Aucun utilisateur trouvé.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajouter/Modifier -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-yellow-600">
                {{ showAddModal ? 'Ajouter un utilisateur' : 'Modifier un utilisateur' }}
            </h2>
            <form @submit.prevent="showAddModal ? addUser() : updateUser()">
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Nom</label>
                    <input v-model="form.name" type="text" class="w-full border rounded px-3 py-2"  />
                    <p class="text-red-500" v-if="errorName">{{ errorName }}</p>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Email</label>
                    <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2"  />
                    <p class="text-red-500" v-if="errorMail">{{ errorMail }}</p>
                </div>
                <div class="mb-4" v-if="showAddModal">
                    <label class="block mb-1 font-semibold">Mot de passe</label>
                    <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2"  />
                    <p v-if="errorPass" class="text-red-500">{{ errorPass }}</p>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Rôle</label>
                    <select v-model="form.role" class="w-full border rounded px-3 py-2" >
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div v-if="error" class="text-red-500">{{ error }}</div>
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
const users = ref([])
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const form = ref({ name: '', email: '', password: '', role: 'user', id: null })
let userToDelete = null

const errorMail = ref('')
const errorPass = ref('')
const errorName = ref('')
const error = ref(null)

onMounted(fetchUsers)

async function fetchUsers() {
    try {
        const res = await fetch('/api/admin/users', {
            headers: { 
                'Content-Type': 'application/json', 
                'authorization': `Bearer ${token}`
            },
    })
        if (!res.ok) throw new Error(res.statusText)
        users.value = await res.json()
    } catch (err) {
        console.error('Fetch users failed:', err)
    }
}

function closeModal() {
    showAddModal.value = false
    showEditModal.value = false
    form.value = { name: '', email: '', role: 'user', id: null }
}

function editUser(user) {
    form.value = {
        name: user.name_usersone,
        email: user.email_usersone,
        role: user.role_usersone,
        password: user.password_usersone,
        id: user.id
    }
    showEditModal.value = true
}

async function verifyPattern(){
    if (!form.value.name || !form.value.email || !form.value.password) {
        error.value = 'Tous les champs sont obligatoires.'
        return
    }
    if (!/^[A-Za-z\s]+$/.test(form.value.name)) {
        errorName.value = 'Le nom ne doit contenir que des lettres.'
        return
    }
    if (!/\S+@\S+\.\S+/.test(form.value.email)) {
        errorMail.value = 'Adresse email invalide.'
        return
    }
    if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/.test(form.value.password)) {
        errorPass.value = 'Le mot de passe doit contenir 8 caractères, dont 1 majuscule, 1 minuscule et 1 chiffre.'
        return
    }
}
const token = localStorage.getItem('token')
async function addUser() {
    verifyPattern()
    try {
        const res = await fetch('/api/admin/users/store', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                name: form.value.name,
                email: form.value.email,
                password: form.value.password,
                role: form.value.role
            })
        })
        if (!res.ok) throw new Error('Erreur lors de l\'ajout')
        const newUser = await res.json()
        users.value.push({
            id: newUser.id ,
            name_usersone: newUser.name_usersone || form.value.name,
            email_usersone: newUser.email_usersone || form.value.email,
            role_usersone: newUser.role_usersone || form.value.role
        })
        closeModal()
    } catch (err) {
        alert('Erreur lors de l\'ajout')
    }
}

async function updateUser() {
    verifyPattern()
    console.log('Updating user with data:', form.value);
    try {
        const res = await fetch(`/api/admin/users/update/${form.value.id}`, {
            method: 'PUT',
            headers: { 
                'Content-Type': 'application/json', 
                'authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                name: form.value.name,
                email: form.value.email,
                password: form.value.password,
                role: form.value.role
            })
        })
        if (!res.ok) throw new Error('Erreur lors de la modification')
        const updatedUser = await res.json()
        const index = users.value.findIndex(u => u.id === updatedUser.id)
        if (index !== -1) {
            users.value[index] = updatedUser
        }
        closeModal()
    } catch (err) {
        alert('Erreur lors de la modification')
    }
}

function deleteUser(id) {
    showDeleteModal.value = true
    pathName.value = `/api/admin/users/destroy/${id}`
    userToDelete = id
}

function handleDeleteConfirmed() {
    if (userToDelete !== null) {
        users.value = users.value.filter(user => user.id !== userToDelete)
        userToDelete = null
    }
    showDeleteModal.value = false
}
</script>
