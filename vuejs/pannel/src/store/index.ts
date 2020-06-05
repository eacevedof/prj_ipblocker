import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    navhamburguer: false,
    globalx: "Soy la global",
  },
  mutations: {
    set_globalx(state,value){
      state.globalx = value
    },

    set_navhamburger(state){
      state.navhamburguer = !state.navhamburguer
    }

  },
  actions: {
  },
  modules: {
  },
});
