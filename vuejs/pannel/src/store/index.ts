import Vue from 'vue';
import Vuex from 'vuex';
import Axios from 'axios';
import api from "@/providers/api.ts"
import db from "@/helpers/localdb.ts"
import Localdb from '@/helpers/localdb.ts';

Vue.use(Vuex);

export default new Vuex.Store({
  
  state: {
    sidebar: false,
    globalx: "Soy la global",
    customers: [],
    myip: "",
    islogged: false,
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
    },

    set_myip(state, data){
      state.myip = data
    },

    set_islogged(state, islogged){
      state.islogged = islogged
    }

  },

  //lo comiteable
  actions: {

    get_customers: async function({ commit }){
      console.log("async get_customers")
      const data = await fetch("http://json.theframework.es/index.php?getfile=app_costumer.json");
      const customers = await data.json()
      commit("set_customers",customers)
    },

    async_get_myip: async function({commit}){
      await fetch('https://api.ipify.org?format=json')
      .then(x => x.json())
      .then(({ ip }) => {
        console.log("get_async_ip",ip)
        commit("set_myip",ip) 
      });
    },

    async_islogged: async function({commit}){
      const usertoken = db.select("usertoken")
      if(!usertoken){
        commit("set_islogged",false)
        return
      }

      const response = await api.async_is_validtoken(usertoken)
      console.log("store.async_islogged.response",response)

      if(!response.data || response.error) {
        commit("set_islogged",false)
        return
      }
      
      if(response.data.result == true) commit("set_islogged",true)
    }
    
  },

  modules: {

  },

});
