
import { createRouter, createWebHistory } from 'vue-router';
import CustomRoutes from '../custom/router/custom';
import AdminRoutes from '../admin/router/Admin';
const routes = [
    ...CustomRoutes,
    ...AdminRoutes
]

const router = createRouter({
    history: createWebHistory(),
    routes
})
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')

    if (to.meta.requiresAuth && !token) {
        // on se souvient de la route et on bascule sur /login
        return next({
            path: '/login',
            query: { redirect: to.fullPath }
        })
    }
    next()
})

export default router;