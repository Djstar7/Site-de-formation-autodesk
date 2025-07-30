<template>
    <aside class="w-64 fixed h-[100vh] bg-gray-900 text-white flex flex-col py-6 px-4">
        <nav class="flex flex-col space-y-4">
            <router-link
                to="/admin/dashboard"
                :class="['flex items-center px-3 py-2 rounded font-semibold', $route.path === '/admin/dashboard' ? 'bg-gray-800' : 'hover:bg-gray-700']"
            >
                <span class="material-icons mr-2">dashboard</span>
                Tableau de bord
            </router-link>
            <router-link 
                to="/admin/gettrainings" 
                :class="['flex items-center px-3 py-2 rounded font-semibold', $route.path === '/admin/gettrainings' ? 'bg-gray-800' : 'hover:bg-gray-700']">
                <span class="material-icons mr-2">school</span>
                Cours Autodesk
            </router-link>
            <router-link 
                to="/admin/getplans" 
                :class="['flex items-center px-3 py-2 rounded hover:bg-gray-700', $route.path === '/admin/getplans' ? 'bg-gray-800' : 'hover:bg-gray-700']">
                <span class="material-icons mr-2">home</span>
                Plans de maison
            </router-link>
            <router-link 
                to="/admin/getusers" 
                :class="['flex items-center px-3 py-2 rounded hover:bg-gray-700', $route.path === '/admin/getusers' ? 'bg-gray-800' : 'hover:bg-gray-700']">
                <span class="material-icons mr-2">people</span>
                Utilisateurs
            </router-link>
        </nav>
    </aside>
</template>
<script setup>  

</script>