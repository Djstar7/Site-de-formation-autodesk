<template>
  <header class="bg-yellow-500 py-6">
    <div class="container mx-auto text-center px-4">
      <h1 class="text-4xl font-bold text-white">Formations</h1>
      <p class="text-lg mt-2 text-gray-800">
        Bienvenue sur la page des formations. Découvrez nos offres !
      </p>
    </div>
  </header>

  <div class="container mt-8 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="training in trainings"
        :key="training.id"
        class="border p-4 rounded shadow bg-white w-full h-full flex flex-col justify-between hover:shadow-lg transition duration-300 ease-in-out"
      >
        <!-- Vidéo -->
        <div
          class="relative w-full aspect-video overflow-hidden rounded-md group cursor-pointer"
          @mouseenter="handleMouseEnter(training.id)"
          @mouseleave="handleMouseLeave(training.id)"
          @click="viewDetails(training.id)"
        >
          <video
            :ref="el => setVideoRef(el, training.id)"
            :src="training.video_trainings"
            muted
            loop
            autoplay
            :controls="false"
            class="object-cover w-full h-full"
            @timeupdate="restartAfterThirtySeconds(training.id)"
          ></video>

          <div
            v-if="hovering !== training.id || !isPlayingMap[training.id]"
            class="absolute inset-0 flex items-center justify-center bg-black/20"
          >
            <div class="text-white opacity-80 text-6xl pointer-events-none">▶</div>
          </div>
        </div>

        <!-- Infos formation -->
        <h3 class="text-xl font-semibold mt-3 mb-2 text-gray-600 truncate">
          {{ training.title_trainings }}
        </h3>
        <p class="text-lg font-bold mb-4 text-yellow-500">
          {{ training.price_trainings }} $
        </p>

        <!-- Boutons -->
        <div class="flex flex-col sm:flex-row justify-between mt-auto space-y-2 sm:space-y-0 sm:space-x-2">
          <button
            class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full sm:w-auto flex-1 truncate text-sm md:text-base"
            @click="ordersTraining(training.id)"
          >
            Commander
          </button>
          <button
            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 w-full sm:w-auto flex-1 truncate text-sm md:text-base"
            @click="viewDetails(training.id)"
          >
            Détails
          </button>
        </div>
      </div>
    </div>

    <modal-get-orders-user
      :show="showSuccess"
      :description="desc"
      @close="showSuccess = false"
    />
  </div>
</template>
<script setup>
import { ref, onMounted, onBeforeMount } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import ModalGetOrdersUser from '../components/GetOrdersUsers.vue'

const trainings = ref([])
const showSuccess = ref(false)
const desc = 'Formation commandée avec succès. \nConsultez vos commandes sur la page commande.'
const router = useRouter()
const route = useRoute()

const videoRefs = ref({})
const isPlayingMap = ref({})
const hovering = ref(null)

const setVideoRef = (el, id) => {
  if (el) videoRefs.value[id] = el
}

// ▶️ Lecture vidéo (on hover + click -> voir détails)
const handleMouseEnter = (id) => {
  const video = videoRefs.value[id]
  if (video) {
    video.currentTime = 0
    video.play()
    isPlayingMap.value[id] = true
    hovering.value = id
  }
}

const handleMouseLeave = (id) => {
  const video = videoRefs.value[id]
  if (video) {
    video.pause()
    video.currentTime = 0
    isPlayingMap.value[id] = false
    hovering.value = null
  }
}

// Boucle toutes les 30 sec max
const restartAfterThirtySeconds = (id) => {
  const video = videoRefs.value[id]
  if (video && video.currentTime >= 30) {
    video.currentTime = 0
    video.play()
  }
}

// Redirection détails
const viewDetails = (id) => {
  router.push({ name: 'training', params: { id } })
}

// Vérification du token
onBeforeMount(() => {
  const token = localStorage.getItem('token')
  if (!token) {
    router.replace({
      path: '/login',
      query: { redirect: route.fullPath }
    })
  }
})

// Charger les formations
onMounted(async () => {
  const token = localStorage.getItem('token')
  try {
    const res = await fetch('/api/trainings', {
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token}`
      }
    })
    if (!res.ok) throw new Error(res.statusText)
    trainings.value = await res.json()
  } catch (err) {
    console.error('Fetch trainings failed:', err)
  }
})

// Commander
const token = localStorage.getItem('token')
const userId = localStorage.getItem('id')

const ordersTraining = async (id) => {
  try {
    const training = trainings.value.find(t => t.id === id)
    if (!training) throw new Error('Training not found')

    const res = await fetch('/api/orders/store', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        userId,
        trainingId: training.id,
        totalPriceOrders: training.price_trainings,
        choix: 'Formation'
      })
    })

    if (!res.ok) throw new Error(`Failed to place order: ${res.statusText}`)
    showSuccess.value = true
  } catch (err) {
    console.error('Error placing order:', err)
  }
}
</script>
