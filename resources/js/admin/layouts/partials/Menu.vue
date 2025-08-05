<template>
  <aside class="w-64  fixed h-[100vh] bg-gray-900 text-white flex flex-col py-6 pl-4">
    <nav class="flex flex-col space-y-4">
      <router-link
        to="/admin/dashboard"
        class="flex items-center pl-3 h-[60px] rounded font-semibold"
        :class="$route.path === '/admin/dashboard' ? 'bg-gray-800' : 'hover:bg-gray-700'"
        title="Tableau de bord"
      >
        <span class="material-icons mr-2">dashboard</span>
        <span v-if="isGetMenu">Tableau de bord</span>
      </router-link>

      <router-link 
        to="/admin/gettrainings" 
        class="flex items-center pl-3 h-[60px] rounded font-semibold"
        :class="$route.path === '/admin/gettrainings' ? 'bg-gray-800' : 'hover:bg-gray-700'"
        title="Formations"
      >
        <span class="material-icons mr-2">school</span>
        <span v-if="isGetMenu">Cours Autodesk</span>
      </router-link>

      <router-link 
        to="/admin/getplans" 
        class="flex items-center pl-3 h-[60px] rounded font-semibold"
        :class="$route.path === '/admin/getplans' ? 'bg-gray-800' : 'hover:bg-gray-700'"
        title="Plans de maison"
      >
        <span class="material-icons mr-2">home</span>
        <span v-if="isGetMenu">Plan de maison</span>
      </router-link>

      <router-link 
        to="/admin/getusers" 
        class="flex items-center pl-3 h-[60px] rounded font-semibold"
        :class="$route.path === '/admin/getusers' ? 'bg-gray-800' : 'hover:bg-gray-700'"
        title="Utilisateurs"
      >
        <span class="material-icons mr-2">people</span>
        <span v-if="isGetMenu">Utilisateurs</span>
      </router-link>

      <router-link 
        to="/admin/getorders" 
        class="flex items-center pl-3 h-[60px] rounded font-semibold"
        :class="$route.path === '/admin/getorders' ? 'bg-gray-800' : 'hover:bg-gray-700'"
        title="Commandes"
      >
        <span class="material-icons mr-2">receipt_long</span>
        <span v-if="isGetMenu">Commandes</span>
      </router-link>
    </nav>
  </aside>
</template>

<script setup>
defineProps({
  isGetMenu: {
    type: Boolean,
    default: true
  }
})
</script>
