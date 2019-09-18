import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/vlogin',
            name: '/vlogin',
            component: () => import('./views/Login.vue')
        },
        {
            path: '/vhome',
            name: '/vhome',
            component: () => import('./views/Home.vue')
        },
        {
            path: '/varea',
            name: '/varea',
            component: () => import('./views/Area.vue')
        },
        {
            path: '/vpart',
            name: '/vpart',
            component: () => import('./views/Part.vue')
        },
        {
            path: '/vgroup',
            name: '/vgroup',
            component: () => import('./views/Group.vue')
        },
        {
            path: '/vuser',
            name: '/vuser',
            component: () => import('./views/User.vue')
        },
        {
            path: '/vuserp',
            name: '/vuserp',
            component: () => import('./views/Userp.vue')
        },
        {
            path: '/vrole',
            name: '/vrole',
            component: () => import('./views/Role.vue')
        },
        {
            path: '/vgrid',
            name: '/vgrid',
            component: () => import('./views/Grid.vue')
        },
        {
            path: '/vdata',
            name: '/vdata',
            component: () => import('./views/Data.vue')
        },
        {
            path: '/vcount',
            name: '/vcount',
            component: () => import('./views/Count.vue')
        },
        {
            path: '/vback',
            name: '/vback',
            component: () => import('./views/Back.vue')
        },
        {
            path: '/vresult',
            name: '/vresult',
            component: () => import('./views/Result.vue')
        },
        {
            path: '/',
            redirect: '/vlogin'
        },
    ]
});

// 解决点击重复路由出现错误提示的问题
const originalPush = Router.prototype.replace;
Router.prototype.replace = function replace(location) {
    return originalPush.call(this, location).catch(err => err)
};

export default router;
