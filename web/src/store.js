import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        types: [],
        menus: [],
        visit: null,
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
        timeSet(state, visit) {
            state.roles = visit
        },
    },
    actions: {}
})
