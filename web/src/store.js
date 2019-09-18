import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        types: [],
        menus: [],
    },
    mutations: {
        userSet(state, user) {
            state.user = user
        },
        typeSet(state, types) {
            state.types = types
        },
        menuSet(state, menus) {
            state.menus = menus
        },
        roleSet(state, roles) {
            state.roles = roles
        },
    },
    actions: {}
})
