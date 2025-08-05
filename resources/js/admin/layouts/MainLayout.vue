<template>
  <div class="min-h-screen bg-gray-800 flex flex-col">
    <!-- Header -->
    <Header />

    <div class="flex flex-1 mt-20 relative">
      <!-- Sidebar Menu -->
      <div
        :class="[
          'bg-gray-900 text-white flex-shrink-0 h-full transition-all duration-500 relative',
          isGetMenu ? 'w-64' : 'w-16'
        ]"
      >
        <Menu :isGetMenu="isGetMenu" />        
      </div>
      <!-- Toggle Button flottant -->
        <div
          :class="['fixed top-20 z-50 bg-gray-800 hover:bg-gray-700 p-1 shadow-lg cursor-pointer transition-all duration-500', isGetMenu ? 'left-64' : 'left-20']"
          @click="toggleMenu"
        >
          <span class="material-icons text-white text-sm">
            {{ isGetMenu ? 'chevron_left' : 'chevron_right' }}
          </span>
        </div>

      <!-- Main Content -->
      <main class="flex-1 p-4 pt-0 transition-all duration-300 relative">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import Menu from './partials/Menu.vue'
import Header from './partials/Header.vue'
import { ref } from 'vue'

const isGetMenu = ref(false)

const toggleMenu = () => {
  isGetMenu.value = !isGetMenu.value
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
</style>
