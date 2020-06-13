import Vue from 'vue';
import Vuex from 'vuex';
import api from "@/providers/api.ts"
import db from "@/helpers/localdb.ts"

Vue.use(Vuex);

const is_undefined = mxvar => (typeof mxvar == "undefined")

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
        return { error: "Not token found"}
      }

      const response = await api.async_is_validtoken()
      //alert("store.async_islogged.async_is_validtoken.response raw:"+JSON.stringify(response))
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
