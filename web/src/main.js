import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import md5 from 'js-md5'
import './plugins/iview.js'

import axios from './libs/axios'

// 跨域
Vue.prototype.$ = axios.ajax;
Vue.prototype.$.all = axios.all;
Vue.prototype.$.spread = axios.spread;

// MD5
Vue.prototype.$md5 = md5;


import devArticle from './components/dev-article.vue'

Vue.config.productionTip = false;

Vue.component('dev-article', devArticle);

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app');
