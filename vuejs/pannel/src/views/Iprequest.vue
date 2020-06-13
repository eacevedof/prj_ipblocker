<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />
    
    <notierror v-if="error.title!=''" :title="error.title"  :message="error.message" />
    <list v-if="error.title==''" />
    
  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import api from "../providers/api"
import db from "../helpers/localdb"

import Scrumbs from "@/components/navigation/Scrumbs.vue"
import list from "@/modules/iprequest/list.vue"
import notierror from "@/components/common/notifications/notification_error.vue"

export default {

  name: "iprequest",
  
  components: {
    Scrumbs,
    list,
    notierror,
  },

  data(){
    return {
      error:{ title:"", message:""}
    }
  },

  beforeMount: async function (){
    this.reset_error()
    const response = await this.async_islogged()
    console.log("iprequest.beforeMount.async_islogged.response RAW",response)

    if(response.error){
      //alert(response.error)
      this.set_error("Error",response.error)
    }

    if(!this.islogged)
      this.$router.push({name:"login"})
  },

  mounted: async function(){
    console.log("iprequest mounted")
    //await this.async_islogged()
    //if(!this.islogged)
      //this.$router.push({name:"login"})
  },

  computed:{
    //getters
    ...mapState(["islogged"]),
  },

  methods:{
    //setters
    ...mapActions(["async_islogged"]),
    set_error(title,message){
      this.error.title = title
      this.error.message = message;
    },
    
    reset_error(){
      this.error = { title:"", message:""}
    },    
  }
}
</script>