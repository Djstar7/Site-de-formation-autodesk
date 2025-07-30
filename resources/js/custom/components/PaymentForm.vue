
<template>
    <div class="flex flex-col items-center justify-center min-h-screen text-white">
        <div class="w-full max-w-md p-6 bg-gray-900 rounded-lg shadow-lg">
            <div class="mt-4">
                <h3 class="text-lg font-semibold text-yellow-500 mb-2">Paiement</h3>
                <label for="phone" class="block mb-1">Numéro de téléphone :</label>
                <input id="phone" v-model="phone" type="text" autocomplete="phone" placeholder="6XXXXXXXX"
                    class="w-full p-2 mb-4 bg-gray-700 text-white rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" />
                <span v-if="errorPhone" class="text-red-500 text-sm">{{ errorPhone }}</span>
                <button @click.prevent="pay()" class="w-full py-2 bg-yellow-500 text-gray-800 font-bold rounded hover:bg-yellow-600">
                    Valider->
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const nameUser = localStorage.getItem('name')
// const emailUser = localStorage.getItem('email')
const emailUser = "infodjstar7@gmail.com";

const phone = ref('')

const errorPhone = ref('')

async function validateInputs() {
    // Reset errors
    errorPhone.value = ''

    if (!phone.value) {
        errorPhone.value = 'Le numéro est obligatoire.'
        return false
    }
    if (!/^6[0-9]{8}$/.test(phone.value)) {
        errorPhone.value = 'Le numéro doit Commencer par 6 et contenir 9 chiffres.'
        return false
    }
    return true
}
async function pay() {
    const isValid = await validateInputs()
    if (!isValid) return
    const prix = route.params.prix
    const token = localStorage.getItem('token')
    const payload = {
        amount: 100,
        phone: '237'+phone.value,
        name: nameUser,
        email: emailUser,
    }
    console.log('En cour de traitement')
    console.log('prix  '+100+'   phone  '+phone.value+'   name  '+nameUser+'   email  '+emailUser);
    const res = await fetch('/api/pay/intierer' , {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(payload)
    })
    // juste après le fetch()
    if (res.status === 400) {
        const err = await res.json();
        console.error('Réponse 400 complète du back :', err);
    // err.raw_body contient le JSON brut de CinetPay
    alert(
        'CinetPay a renvoyé une erreur 400 :\n' +
        (err.api_response?.message || err.raw_body || JSON.stringify(err))
    );
        return;
    }

    if (!res.ok) {
    const text = await res.text();
    throw new Error(`Erreur serveur ${res.status} : ${text}`);
    }

    console.log('Enregistré avec succès : ' + JSON.stringify(payload));
    const data = await res.json()

    if (data.success && data.payment_url) {
        window.location.href = data.payment_url;
    } else {
        alert('Erreur de paiement : ' + (data.message || 'inconnue'))
    }
}
</script>
