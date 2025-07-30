<template>
    <div class="min-h-screen bg-gray-800 flex flex-col">
        <!-- Header -->
        <Header />

        <div class="flex flex-1 mt-20" >
            <!-- Sidebar Menu -->
            <Menu class="w-64 bg-gray-900 text-white flex-shrink-0" />

            <!-- Main Content -->
            <main class="flex-1 p-8 ml-64">
                <router-view />
            </main>
        </div>

    </div>
</template>

<script setup>
import Menu from './partials/Menu.vue'
import Header from './partials/Header.vue'
</script>
<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
</style>