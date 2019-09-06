import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/vuelogin',
            name: '/vuelogin',
            component: () => import('./views/Login.vue')
        },
        {
            path: '/vuehome',
            name: '/vuehome',
            component: () => import('./views/Home.vue')
        },
        {
            path: '/vueuser',
            name: '/vueuser',
            component: () => import('./views/User.vue')
        },
        {
            path: '/vuegrid',
            name: '/vuegrid',
            component: () => import('./views/Grid.vue')
        },
        {
            path: '/vuedata',
            name: '/vuedata',
            component: () => import('./views/Data.vue')
        },
        {
            path: '/vuecount',
            name: '/vuecount',
            component: () => import('./views/Count.vue')
        },
        {
            path: '/vueresult',
            name: '/vueresult',
            component: () => import('./views/Result.vue')
        },
        {
            path: '/',
            redirect: '/vuelogin'
        },
    ]
})
