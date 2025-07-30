<template>
    <div class="w-72 bg-gray-800 text-yellow-500 p-4 rounded-r-xl flex flex-col justify-between" :style="{ display: isMenuOpen ? 'block' : 'none' }">
        <div >
            <!-- Logo -->
            <div class="mb-8 flex items-center justify-center">
                <Logo />
                <span class="ml-2 text-lg font-bold">Civil Design</span>
            </div>
            <!-- Profile -->
            <div class="mt-6 flex items-center space-x-2 px-4 py-2"  v-if="name">
                <img src="https://i.pravatar.cc/40?img=68" alt="Profile" class="w-8 h-8 rounded-full">
                <span>{{ name }}</span>
            </div>
            <!-- Menu -->
            <nav class="space-y-2">
                <router-link to="/" class="flex items-center justify-between hover:bg-gray-700 px-4 py-2 rounded-lg" :class="{ 'bg-gray-700': $route.name === 'home' }">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 20v-6H6v6H2V10l8-8 8 8v10h-4v-6h-4v6z"/></svg>
                        <span>Accueil</span>
                    </div>
                    <span class="text-sm bg-yellow-400 rounded-full px-2 py-0.5 text-white">5</span>
                </router-link>
                <router-link to="/plans" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700 rounded-lg" :class="{ 'bg-gray-700': $route.path.startsWith('/plans') }">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2L2 7v11h16V7l-8-5zm0 2.18L15.64 7H4.36L10 4.18zM4 9h12v7H4V9z"/></svg>
                        <span>Plans</span>
                    </div>
                </router-link>
                <router-link to="/trainings" class="flex items-center justify-between px-4 py-2 hover:bg-gray-700 rounded-lg" :class="{ 'bg-gray-700': $route.path.startsWith('/trainings') }">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 00-1 1v2H7a1 1 0 000 2h2v2H7a1 1 0 000 2h2v2H7a1 1 0 000 2h2v2a1 1 0 002 0v-2h2a1 1 0 000-2h-2v-2h2a1 1 0 000-2h-2V7h2a1 1 0 000-2h-2V3a1 1 0 00-1-1z"/></svg>
                        <span>Formations</span>
                    </div>
                </router-link>
                <router-link v-if="name" :to="ordersPath" class="flex items-center space-x-2 justify-between px-4 py-2 hover:bg-gray-700 rounded-lg" :class="{ 'bg-gray-700': $route.path.startsWith(ordersPath) }">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3h14a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V5a2 2 0 012-2zm0 2v10h14V5H3zm2 2h10v2H5V7zm0 4h6v2H5v-2z"/></svg>
                        <span>Commande</span>
                    </div>
                </router-link>
                <router-link v-if="name" to="/history" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700 rounded-lg" :class="{ 'bg-gray-700': $route.path.startsWith('/history') }">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2a10 10 0 1010 10A10 10 0 0012 2zm1 15h-2v-6h2zm0-8h-2V7h2z"/>
                        </svg>
                        <span>Historiques</span>
                    </div>

                </router-link>
                <router-link v-if="!name" to="/orders" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700 rounded-lg" :class="{ 'bg-gray-700': $route.path.startsWith('/orders') }">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3h14a2 2 0 012 2v10a2 2 0 01-2 2H3a2 2 0 01-2-2V5a2 2 0 012-2zm0 2v10h14V5H3zm2 2h10v2H5V7zm0 4h6v2H5v-2z"/></svg>
                        <span>Commandes</span>
                    </div>
                </router-link>
                <router-link v-if="!name" to="/users" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700 rounded-lg" :class="{ 'bg-gray-700': $route.path.startsWith('/users') }">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 10a4 4 0 100-8 4 4 0 000 8zm-6 8a6 6 0 1112 0H4z"/></svg>
                        <span>Utilisateurs</span>
                    </div>
                </router-link>
                <router-link to="/about" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700 rounded-lg" :class="{'bg-gray-700': $route.name === 'about'}">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2a10 10 0 1010 10A10 10 0 0012 2zm0 18a8 8 0 110-16 8 8 0 010 16zm-1-9h2v5h-2zm0-4h2v2h-2z"/>
                        </svg>
                        <span>A propos</span>
                    </div>
                </router-link>
            </nav>
        </div>

    <!-- Profile -->

    </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import Logo from '../../components/Logo.vue';

const props = defineProps({
    isMenuOpen: {
        type: Boolean,
        default: true,
    },
})
const name = localStorage.getItem('name')

// Récupère l’ID uniquement quand on est en client
const userId = ref(null)
if (typeof window !== 'undefined') {
  userId.value = localStorage.getItem('id')
}

// (optionnel) pour construire le path

const ordersPath = computed(() => `/orders/${userId.value}`)


</script>
