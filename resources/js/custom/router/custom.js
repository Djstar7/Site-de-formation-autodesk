// Section Custom
import Home from '../pages/Home.vue'
import Trainings from '../pages/Trainings.vue'
import Plans from '../pages/Plans.vue'
import About from '../pages/About.vue'
import Historys from '../pages/Historys.vue'
import Orders from '../pages/Orders.vue'
import GetPlan from '../pages/store/GetPlan.vue'
import GetTraining from '../pages/store/GetTraining.vue';

import Login from '../auth/Login.vue'
import Register from '../auth/Register.vue'

import PaymentForm from '../pages/PaymentForm.vue'



export default  [
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
        component: GetPlan,
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
        component: GetTraining,
    },
    {
        path: '/history',
        name: 'history',
        component: Historys,
    },
    {
        path: '/orders/:userId',
        name: 'order',
        component: Orders,
        meta: { requiresAuth: true },
    },
    {
        path: '/pay',
        name: 'pay',
        component: PaymentForm,
        meta: { requiresAuth: true },
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
]

