import { createRouter, createWebHistory } from 'vue-router';
import Home from '../Pages/Home.vue';
import About from '../Pages/About.vue';

import Plans from '../Pages/Users/Plans.vue';
import Trainings from '../Pages/Users/Trainings.vue';
import Historys from '../Pages/Users/Historys.vue';
import OrdersUsers from '../Pages/Users/OrdersUsers.vue';
import PayementForm from '../Pages/Users/PaymentForm.vue'

import PlanDetails from '../Pages/Users/Details/Plan.vue'
import TrainingDetails from '../Pages/Users/Details/Training.vue'

import Orders from '../Pages/Admin/Orders.vue';
import Users from '../Pages/Admin/Users.vue';

import Login from '../Components/Login.vue';
import Register from '../Components/Register.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/plans',
        name: 'plans',
        component: Plans,
        meta: { requiresAuth: true },
    },
    {
        path: '/plans/:id',
        name: 'plan',
        component: PlanDetails,
    },
    {
        path: '/trainings',
        name: 'trainings',
        component: Trainings,
        meta: { requiresAuth: true },
    },
    {
        path: '/trainings/:id',
        name: 'training',
        component: TrainingDetails,
    },
    {
        path: '/history',
        name: 'history',
        component: Historys,
    },
    {
        path: '/orders',
        name: 'orders',
        component: Orders,
    },
    {
        path: '/orders/:userId',
        name: 'order',
        component: OrdersUsers,
        meta: { requiresAuth: true },
    },
    {
        path: '/pay',
        name: 'pay',
        component: PayementForm,
        meta: { requiresAuth: true },
    },
    {
        path: '/users',
        name: 'users',
        component: Users,
    },

    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            title: 'Page de connexion',
        },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
    {
        path: '/about',
        name: 'about',
        component: About,
    },
    // { path: '/:catchAll(.*)', redirect: '/login' }
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});
// ⬇️ Place ce guard **juste après** la création du router
// 👇 guard globale
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
