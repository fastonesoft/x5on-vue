import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import md5 from 'js-md5'
import './plugins/iview.js'

import axios from './libs/axios'
import devArticle from './components/dev-article.vue'

// 跨域
Vue.prototype.$ = axios.ajax;
Vue.prototype.$.all = axios.all;
Vue.prototype.$.spread = axios.spread;

// MD5
Vue.prototype.$md5 = md5;

// Vue自带的
Vue.config.productionTip = false;

// 自定义模板
Vue.component('dev-article', devArticle);

// 全局路由守卫
router.beforeEach((to, from, next) => {
    // 检测to.name是否在前端菜单列表当中
    // 不在列表中，跳转登录
    // 路由鉴权，暂时不做
    // if (to.name !== '/vuelogin' && store.state.user === null) {
    //     next('/vuelogin');
    // } else {
        next();
    // }
});

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
