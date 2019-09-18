import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './plugins/iview.js'

import axios from './libs/axios'
import devArticle from './components/dev-article.vue'

import xcon from './libs/xcon'

// 跨域
Vue.prototype.$ = axios.ajax;
Vue.prototype.$.all = axios.all;
Vue.prototype.$.spread = axios.spread;

// Vue自带的
Vue.config.productionTip = false;

// 自定义模板
Vue.component('dev-article', devArticle);

// 全局路由守卫
router.beforeEach((to, from, next) => {
    // 本地信息
    store.replaceState(Object.assign({}, store.state, xcon.stateRead()));
    // 菜单鉴权
    let menus = store.state.menus;
    let normal = ['/vlogin'];
    if (normal.filter(item => item === to.name).length === 0 && menus.filter(menu => menu.name === to.name).length === 0) {
        // 即不在首页、登录页，也不在菜单列表当中
        if (!from.name) {
            // 清除登录状态、强制登录
            xcon.stateClear();
            next('/vlogin')
        } else {
            if (menus.filter(menu => menu.name === from.name).length > 0) {
                next(from.name)
            } else {
                xcon.stateClear();
                next('/vlogin')
            }
        }
    } else {
        next();
    }
});

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
