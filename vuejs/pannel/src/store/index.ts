import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    navhamburguer: false,
    globalx: "Soy la global",
  },
  mutations: {
    setGlobalx(state,value){
      state.globalx = value
    }
  },
  actions: {
  },
  modules: {
  },
});
