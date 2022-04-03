import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state : {
        user: false,
        userPermission: null,
        deleteFlag:false,
        route:null

    },
    getters: {
        getUserPermission(state){
            return state.userPermission
        },
        getUser(state)
        {
            return state.user
        },
        getDeleteFlag(state)
        {
            return state.deleteFlag
        },
        getRoute(state){
            return state.route;
        }

    },
    mutations: {
        setUpdateUser(state, data){
            state.user = data
        },
        setUserPermission(state, data){
            state.userPermission = data
        },
        setDeleteFlag(state,data)
        {
            state.deleteFlag = data
        },
        setRoute(state,data){
            state.route = data;
        }


    },
})
