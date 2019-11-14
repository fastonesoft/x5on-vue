import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

const router = new Router({
    // mode: 'history',
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
            path: '/vallot',
            name: '/vallot',
            component: () => import('./views/Allot.vue')
        },
        {
            path: '/vcexamed',
            name: '/vcexamed',
            component: () => import('./views/CExamed.vue')
        },
        {
            path: '/vdata',
            name: '/vdata',
            component: () => import('./views/Data.vue')
        },
        {
            path: '/vdataed',
            name: '/vdataed',
            component: () => import('./views/Dataed.vue')
        },
        {
            path: '/vcount',
            name: '/vcount',
            component: () => import('./views/Count.vue')
        },
        {
            path: '/vcounted',
            name: '/vcounted',
            component: () => import('./views/Counted.vue')
        },
        {
            path: '/vc2exam',
            name: '/vc2exam',
            component: () => import('./views/C2Exam.vue')
        },
        {
            path: '/vdocu',
            name: '/vdocu',
            component: () => import('./views/Docu.vue')
        },
        {
            path: '/vback',
            name: '/vback',
            component: () => import('./views/Back.vue')
        },
        {
            path: '/vbacked',
            name: '/vbacked',
            component: () => import('./views/Backed.vue')
        },
        {
            path: '/vstep',
            name: '/vstep',
            component: () => import('./views/Stepp.vue')
        },
        {
            path: '/vstepm',
            name: '/vstepm',
            component: () => import('./views/Stepm.vue')
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
