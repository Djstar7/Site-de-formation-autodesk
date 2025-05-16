<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Connection</h2>
            <form @click.prevent="handleLogin">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        v-model="form.email"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm"
                        placeholder="Enter your email"
                    />
                    <p v-if="errorMail" class="mt-2 text-red-500">{{ errorMail }}</p>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input
                        type="password"
                        v-model="form.password"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm"
                        placeholder="Enter your password"
                    />
                    <p v-if="errorPass" class="mt-2 text-red-500">{{ errorPass }}</p>
                </div>
                <button
                    type="submit"
                    class="w-full bg-yellow-500 text-white py-2 px-4 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                >
                    Se connecter
                </button>
            </form>
            <p class="mt-4 text-sm text-center text-gray-600">
                Vous n'avez pas de compte ?
                <router-link to="register" class="text-yellow-500 hover:underline">S'inscrire</router-link>
            </p>
            <p v-if="error" class="mt-2 text-red-500">{{ error }}</p>
            <p v-else-if="success" class="mt-2 text-green-500">{{ success }}</p>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const form = ref({
    email: '',
    password: ''
})
const error = ref(null)
const success = ref(null)
const isLoading  = ref(false)

const handleLogin = async() => {
    if (!form.value.email || !form.value.password) {
        error.value = 'Tous les champs sont obligatoires.'
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

    try {
        console.log("Soumission du formulaire...")

        console.log('Données envoyées', form.value)
        const res = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: form.value.email,
                password: form.value.password
            })
        })
        console.log(`APi response res ${res}`)
        let result = {}
        if (!res.ok) {
            try {
                result = await res.json()
            } catch {
                const txt = await res.text()
                throw new Error(`Erreur ${res.status}: ${txt}`)
            }

            if (res.status === 422 && result.errors) {
                throw new Error(Object.values(result.errors).flat().join(' '))
            }

            throw new Error(result.message || `Erreur inattendue (${res.status})`)
        }

        result = await res.json()
        console.log('🔑 token reçu :', result.token)

        // ⬇️ juste après avoir obtenu le token et l’objet user
        localStorage.setItem('token', result.token)
        localStorage.setItem('name', result.name)
        localStorage.setItem('email', result.email)
        localStorage.setItem('id', result.id)

        console.log('💾 token en storage :', localStorage.getItem('token'))

        success.value = result.message || 'Connection réussie !'

        // → récupère redirect, ou '/plans' par défaut
        const redirectTo = route.query.redirect || '\plans'
        setTimeout(() => router.push(redirectTo), 1500)

    } catch (err) {
        error.value = err.message
        console.error('Connexion failed:', err)
    } finally {
        isLoading.value = false
    }
}
</script>
