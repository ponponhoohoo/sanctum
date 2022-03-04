import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
   state: {
       user: {
           token: "null",
           id : "null",
           name : "null",
           auth : false,
       }
   },
   getters : {
       isAuthenticated(state) {
           return !!state.user.auth;
       },
   },
   mutations: {
       setToken(state, payload) {
           state.user.token = payload;
       },
       setAuth(state, payload) {
           state.user.auth = payload;
       },
       setName(state, payload) {
        state.user.name = payload;
    },
       setId(state, payload) {
        state.user.id = payload;
    },
   },
   actions: {
       setUserToken(context, payload) {
        context.commit('setToken', payload);
       },
       setUserAuth(context, payload) {
        context.commit('setAuth', payload);
       },
       setUserName(context, payload) {
        context.commit('setName', payload);
       },
       setUserId(context, payload) {
        context.commit('setId', payload);
    },
   },
   plugins: [createPersistedState(
       { // ストレージのキーを指定
         key: 'appName',
         // ストレージの種類を指定
         storage: window.sessionStorage
       }
   )]
})
