<template>
  <div class="flex flex-col items-center justify-center min-h-screen text-white">
    <div class="w-full max-w-md p-6 bg-gray-900 rounded-lg shadow-lg">
      <div class="mt-4">
        <h3 class="text-lg font-semibold text-yellow-500 mb-2">Paiement</h3>
        <label for="phone" class="block mb-1">Numéro de téléphone :</label>
        <input
          id="phone"
          v-model="phone"
          type="text"
          autocomplete="phone"
          placeholder="6XXXXXXXX"
          class="w-full p-2 mb-4 bg-gray-700 text-white rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
        />
        <span v-if="errorPhone" class="text-red-500 text-sm">{{ errorPhone }}</span>
        <button
          @click.prevent="pay"
          :disabled="loading"
          class="w-full py-2 bg-yellow-500 text-gray-800 font-bold rounded hover:bg-yellow-600"
        >
          {{ loading ? 'Chargement...' : 'Valider' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const nameUser = localStorage.getItem('name');
const emailUser = 'infodjstar7@gmail.com'; // Remplace par localStorage.getItem('email') si nécessaire

const phone = ref('');
const errorPhone = ref('');
const loading = ref(false);

const validateInputs = () => {
  errorPhone.value = '';

  if (!phone.value) {
    errorPhone.value = 'Le numéro est obligatoire.';
    return false;
  }

  if (!/^6[0-9]{8}$/.test(phone.value)) {
    errorPhone.value = 'Le numéro doit commencer par 6 et contenir 9 chiffres.';
    return false;
  }

  return true;
};

const pay = async () => {
  if (!validateInputs()) return;

  loading.value = true;

  const payload = {
    amount: 100,
    phone:  '237'+phone.value,
    name: nameUser,
    email: emailUser,
  };

  try {
    console.log('Envoi des données de paiement :', payload);
    
    const response = await fetch('/api/pay/initier', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      },
      body: JSON.stringify(payload),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message + '    ' + errorData.details || 'Erreur lors de l\'initialisation du paiement');
    }

    const data = await response.json();
    console.log('Réponse du serveur :', data);

    if (data.success && data.payment_url) {
      window.location.href = data.payment_url;
    } else {
      console.log('Erreur de paiement : ' + (data.message || 'inconnue'));
    }
  } catch (error) {
    console.log('Une erreur est survenue : ' + error.message);
  } finally {
    loading.value = false;
  }
};
</script>
