import Vue from 'vue';
import Vuex from 'vuex';
import {is_undefined} from "@/helpers/functions"
import apiip from "@/providers/apiip"
import auth from "@/providers/apiauth"
import db from "@/helpers/localdb.ts"

Vue.use(Vuex);

export default new Vuex.Store({
  
  state: {
    sidebar: false,
    myip: "",
    islogged: false,
  },

  //setters
  mutations: {

    set_sidebar(state, isvisible){
      console.log("set_sidebar.isvisible:",isvisible)
      state.sidebar = isvisible
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

    async_get_myip: async function({commit}){
      const ip = await apiip.async_get_myip()
      commit("set_myip",ip)
    },

    async_islogged: async function({commit}){
      const usertoken = db.select("usertoken")
      if(!usertoken){
        commit("set_islogged",false)
        return { error: "Not token found"}
      }

      const response = await auth.async_is_validtoken()
      console.log("store.async_islogged.async_is_validtoken.response",response)

      if(!is_undefined(response.error)){
        if(response.error.includes("403"))
          commit("set_islogged",false)
        return response
      }

      if(response == true) 
        commit("set_islogged",true)
      
      return response
    }
    
  },

  modules: {

  },

});
