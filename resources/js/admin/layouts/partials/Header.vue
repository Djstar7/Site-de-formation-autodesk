<template>
    <header class="bg-gray-800 text-white fixed w-full px-6 py-4 flex items-center justify-between shadow z-40">
        <div class="flex items-center space-x-4">
            <Logo /> 
            <h1 class="text-2xl font-bold my-auto"> Admin Civil Design</h1>
        </div>
        <div class="flex items-center space-x-4">
            <span class="font-medium">Bienvenue, Admin</span>
            <button class="bg-white text-yellow-500 px-3 py-1 rounded hover:bg-yellow-100 transition">Déconnexion</button>
        </div>
    </header>
</template>
<script setup>
import Logo from '../../components/icons/Logo.vue'
</script>