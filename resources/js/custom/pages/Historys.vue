<template>
    <div class="transaction-history bg-gray-800 text-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-yellow-500">Historique des Transactions</h1>
        <table class="table-auto w-full border-collapse border border-gray-700">
            <thead>
                <tr class="bg-gray-700">
                    <th class="border border-gray-700 px-4 py-2 text-yellow-500">Date</th>
                    <th class="border border-gray-700 px-4 py-2 text-yellow-500">Produit</th>
                    <th class="border border-gray-700 px-4 py-2 text-yellow-500">Montant</th>
                    <th class="border border-gray-700 px-4 py-2 text-yellow-500">Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-700">
                    <td class="border border-gray-700 px-4 py-2">{{ transaction.date }}</td>
                    <td class="border border-gray-700 px-4 py-2">{{ transaction.product }}</td>
                    <td class="border border-gray-700 px-4 py-2">{{ transaction.amount }} €</td>
                    <td class="border border-gray-700 px-4 py-2">
                        <span
                            :class="{
                                'text-green-500': transaction.status === 'Réussi',
                                'text-red-500': transaction.status === 'Échoué',
                                'text-yellow-500': transaction.status === 'En attente',
                            }"
                        >
                            {{ transaction.status }}
                        </span>
                    </td>
                </tr>
                <tr v-if="transactions.length === 0">
                    <td colspan="4" class="text-center py-4 text-gray-400">Aucune transaction trouvée.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const transactions= ref([])

onMounted(async() => {
    try{
        const response = await fetch('api/transactions');
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des données');
            }
            transactions.value = await response.json();
    } catch (error) {
        console.error('Erreur:', error);
    }
    
})

</script>

<style scoped>
.table-auto {
    width: 100%;
    border-collapse: collapse;
}
.table-auto th,
.table-auto td {
    text-align: left;
    padding: 8px;
}
.hover\:bg-gray-700:hover {
    background-color: #2d3748; /* gray-700 */
}
</style>
