<template>
    <header class="shadow">
        <div class="max-w-7xl mx-auto px-1 sm:px-2 lg:px-2">
            <div class="flex justify-between items-center py-3">
                <h1 class="text-2xl font-bold">
                    Accueil
                </h1>
                    <template v-if="!isAuthenticated">
                        <div class="flex items-between space-x-2">
                            <router-link
                                to="login"
                                class="border border-yellow-500 rounded-md px-2 py-2 text-yellow-500 hover:bg-yellow-500 hover:text-white">
                                Se Connecter
                            </router-link>
                            <router-link
                                to="register"
                                class="border border-yellow-500 rounded-md px-2 py-2 text-yellow-500 hover:bg-yellow-500 hover:text-white">
                                S'inscrire
                            </router-link>
                        </div>
                    </template>
                    <template v-else>
                        <button
                            @click="logout"
                            class="border border-yellow-500 rounded-md px-2 py-2 text-yellow-500 hover:bg-yellow-500 hover:text-white">
                            Déconnexion
                        </button>
                    </template>
            </div>
        </div>
    </header>
</template>
<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();
const isAuthenticated = !!localStorage.getItem('token');
function logout() {
    localStorage.clear();
    // rediriger vers la page de login
    router.push('/login');
}
</script>
