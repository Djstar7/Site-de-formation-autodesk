<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Inscription</h2>
            <form @submit.prevent="handleSubmit">
                <!-- Nom -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full px-4 py-2 border rounded-md"
                        placeholder="Entrer votre nom"
                    />
                    <p v-if="errorName" class="mt-2 text-red-600">{{ errorName }}</p>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        class="mt-1 block w-full px-4 py-2 border rounded-md"
                        placeholder="Entrer votre email"
                    />
                    <p v-if="errorMail" class="mt-2 text-red-600">{{ errorMail }}</p>
                </div>

                <!-- Mot de passe -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                        class="mt-1 block w-full px-4 py-2 border rounded-md"
                        placeholder="Entrer votre mot de passe"
                    />
                    <p v-if="errorPass" class="mt-2 text-red-600">{{ errorPass }}</p>
                </div>

                <!-- Confirmation -->
                <div class="mb-6">
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirmer mot de passe</label>
                    <input
                        id="confirm-password"
                        v-model="form.confirmPassword"
                        type="password"
                        autocomplete="new-password"
                        class="mt-1 block w-full px-4 py-2 border rounded-md"
                        placeholder="Confirmer votre mot de passe"
                    />
                    <p v-if="errorPass" class="mt-2 text-red-600">{{ errorPass }}</p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-yellow-600 text-white py-2 rounded-md hover:bg-yellow-700"
                >
                    S'inscrire
                </button>
            </form>

            <p class="mt-4 text-center text-gray-600">
                Vous avez déjà un compte ?
                <router-link to="/login" class="text-yellow-600 hover:underline">Se Connecter</router-link>
            </p>
            <p v-if="error" class="mt-2 text-red-600">{{ error }}</p>
            <p v-if="success" class="mt-2 text-green-500">{{ success }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
    name: '',
    email: '',
    password: '',
    confirmPassword: ''
})
const error = ref(null)
const success = ref(null)
const isLoading = ref(false)

const handleSubmit = async () => {
    error.value = null
    success.value = null

    // 1. Validation
    if (!form.value.name || !form.value.email || !form.value.password || !form.value.confirmPassword) {
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
    if (form.value.password !== form.value.confirmPassword) {
        error.value = 'Les mots de passe ne correspondent pas.'
        return
    }

    if (isLoading.value) return
    isLoading.value = true

    try {
        console.log("Soumission du formulaire...")

        console.log('Données envoyées', form.value)
        const res = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: form.value.name,
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
        success.value = result.message || 'Inscription réussie !'
        // Redirection (facultative) après un court délai :
        setTimeout(() => router.push('/login'), 1500)
    } catch (err) {
        error.value = err.message
        console.error('Register failed:', err)
    } finally {
        isLoading.value = false
    }
}
</script>
