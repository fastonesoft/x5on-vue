import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        menus: [],
        items: [],
        roles: [],
    },
    mutations: {
        userSet(state, user) {
            state.user = user
        },
        menuSet(state, menus) {
            state.menus = menus
        },
        itemSet(state, items) {
            state.items = items
        },
        roleSet(state, roles) {
            state.roles = roles
        },
    },
    actions: {}
})
