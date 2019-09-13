import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        count: -1,
    },
    getters: {
        user(state) {
            return state.user;
        }
    },
    mutations: {
        userSet(state, user) {
            state.user = user;
        },
        increase(state) {
            state.count++;
        }
    },
    actions: {}
})
