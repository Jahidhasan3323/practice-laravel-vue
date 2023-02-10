import {createRouter, createWebHistory} from 'vue-router'

import Home from "../components/ExampleComponent.vue";
import Dashboard from "../components/Dashboard.vue";

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/home',
        name: 'home',
        component: Home,
    }


]

const router = createRouter({
    history: createWebHistory(import.meta.env.APP_URL), routes,
})

export default router
