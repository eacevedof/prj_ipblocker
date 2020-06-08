import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  
  state: {
    sidebar: false,
    globalx: "Soy la global",
    customers: [],
  },

  //setters
  mutations: {
    set_globalx(state,value){
      state.globalx = value
    },

    set_sidebar(state, isvisible: boolean){
      console.log("set_sidebar.isvisible:",isvisible)
      state.sidebar = isvisible
    },

    set_customers(state, data){
      state.customers = data
    }

  },

  //lo comiteable
  actions: {
    get_customers: async function({ commit }){
      console.log("async get_customers")
      const data = await fetch("http://json.theframework.es/index.php?getfile=app_costumer.json");
      const customers = await data.json()
      commit("set_customers",customers)
    }
  },

  modules: {

  },

});
