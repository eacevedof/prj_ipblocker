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
    pagetitle: "",
    ipsblacklisted: [],
    myip: "",
    islogged: false,
  },

  //setters
  mutations: {
    
    set_pagetitle(state,value){
      state.pagetitle = value
    },

    set_sidebar(state, isvisible: boolean){
      console.log("set_sidebar.isvisible:",isvisible)
      state.sidebar = isvisible
    },

    set_ipsblacklisted(state, data){
      state.ipsblacklisted = data
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

    async_get_ipsblacklisted: async function({ commit }){
      console.log("async get_ipsblacklisted")
      const data = await fetch("http://json.theframework.es/index.php?getfile=app_costumer.json");
      const ipsblacklisted = await data.json()
      commit("set_ipsblacklisted",ipsblacklisted)
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
