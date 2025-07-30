<template  class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <Menu v-model:isMenuOpen="isMenuOpen" v-if="showHeader" class="md:hidden"/>

        <!-- Main Content -->
        <div class="flex-1 justify-between flex flex-col ">
            <!-- Placeholder for the main content -->
            <div class="bg-gray-800 shadow " v-if="showHeader">
                <!-- Header -->
                <div class="flex items-center w-full px-2">
                    <!-- Toggle Menu Button -->
                    <button @click="toggleMenu" class="text-yellow-500 hover:text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <Header class="w-full text-yellow-500"/>

                </div>
            </div>
            <!-- Main Content Area -->
            <div class="flex-1 ">
                <router-view/>
            </div>
            <!-- Footer -->
            <div>
                <Footer />
            </div>
        </div>
    </div>

</template>
<script setup>
import Footer from './partials/Footer.vue';
import Menu from './partials/Menu.vue';
import Header from './partials/Header.vue';
import { ref, computed } from 'vue';
import {  useRoute } from 'vue-router';

const isMenuOpen = ref(true);
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};


const route = useRoute();

// Liste des routes sans header
const routesSansHeader = ['/login', '/register','/logout']

// Le header ne s'affiche que si la route n'est pas dans la liste
const showHeader = computed(() => !routesSansHeader.includes(route.path) && !route.path.startsWith('/admin'));
</script>
