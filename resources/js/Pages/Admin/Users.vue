<template>
    <div class="bg-yellow-500 flex flex-col items-center">
        <h1 class="text-3xl font-bold text-white">Liste des utilisateurs</h1>
        <p class="text-lg text-gray-800 ">Voici la liste de tous les utilisateurs de notre application.</p>
    </div>
    <div class=" bg-white rounded-lg shadow-md">
        <table class="min-w-full border-collapse border border-gray-800">
            <thead class="bg-yellow-500 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Rôle</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id" class="border-b border-gray-300 hover:bg-yellow-100">
                    <td class="px-4 py-2">{{ user.id }}</td>
                    <td class="px-4 py-2">{{ user.name_usersone }}</td>
                    <td class="px-4 py-2">{{ user.email_usersone }}</td>
                    <td class="px-4 py-2">{{ user.role_usersone }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</template>
<script setup>
import { ref, onMounted } from 'vue'

const users = ref([]);

onMounted(async() => {
    try{
        const res = await fetch('/api/users') // Remplacez l'URL par celle de votre API pour les utilisateurs
        if (!res.ok) throw new Error(res.statusText)
        users.value = await res.json()
    } catch (err) {
        console.error('Fetch users failed:', err)
    }
})
</script>
