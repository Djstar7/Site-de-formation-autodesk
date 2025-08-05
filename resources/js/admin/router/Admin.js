
import AddTraining from '../store/AddTraining.vue';
import AddPlan from '../store/AddPlan.vue';
import AddUser from '../store/AddUser.vue';
import Dashbaord from '../pages/Dashbaord.vue';
import MainLayout from '../layouts/MainLayout.vue';
import GetTrainings from '../pages/GetTrainings.vue'
import GetPlans from '../pages/GetPlans.vue';
import GetUsers from '../pages/GetUsers.vue'
import GetOrders from '../pages/GetOrders.vue';

export default[
    {
        path: '/admin',
        name: 'admin',
        component: MainLayout,
        children: [
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: Dashbaord
            },
            {
                path: 'addtraining',
                name: 'admin.addtraining',
                component: AddTraining
            },
            {
                path: 'addplan',
                name: 'admin.addplan',
                component: AddPlan
            },
            {
                path: 'adduser',
                name: 'admin.adduser',
                component: AddUser
            },
            {
                path: 'gettrainings',
                name: 'admin.gettrainings',
                component: GetTrainings
            },
            {
                path: 'getplans',
                name: 'admin.getplans',
                component: GetPlans
            },
            {
                path: 'getusers',
                name: 'admin.getusers',
                component: GetUsers
            },
            {
                path: 'getorders',
                name: 'admin.getorders',
                component: GetOrders
            }
        ]
    }
]

