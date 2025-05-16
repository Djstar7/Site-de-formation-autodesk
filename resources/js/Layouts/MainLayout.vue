<template  class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <Menu v-model:isMenuOpen="isMenuOpen" class="md:hidden"/>

        <!-- Main Content -->
        <div class="flex-1 justify-between flex flex-col ">
            <!-- Placeholder for the main content -->
            <div class="bg-gray-800 shadow ">
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
import Footer from './Partials/Footer.vue';
import Menu from './Partials/Menu.vue';
import Header from './Partials/Header.vue';
import { ref } from 'vue';
const isMenuOpen = ref(true);
const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

</script>

<!--
// Exemple dans App.vue
<template>
  <div>
    <header v-if="userStore.isAuthenticated">
      Bonjour, {{ userStore.userName }} !
      <button @click="logout">Déconnexion</button>
    </header>
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useUserStore } from '@/stores/user'

const userStore = useUserStore()

onMounted(() => {
  userStore.loadFromLocalStorage()
})


</script> -->
