import Vue from 'vue'
import Router from 'vue-router'

import ajax from './libs/axios'

Vue.use(Router);

const router = new Router({
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
            path: '/vueback',
            name: '/vueback',
            component: () => import('./views/Back.vue')
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
});

// 解决点击重复路由出现错误提示的问题
const originalPush = Router.prototype.replace;
Router.prototype.replace = function replace(location) {
    return originalPush.call(this, location).catch(err => err)
};

// 全局路由守卫
router.beforeEach((to, from, next) => {

    // window.console.log(to);
    // window.console.log(from);

    // ajax.posts('/home/logstatus')
    //     .then(res=>{
    //         window.console.log(res);
    //     });
    // 暂时不做

    next();

});

export default router;
