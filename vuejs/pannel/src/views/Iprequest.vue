<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />
    
    <notierror v-if="error.title!=''" :title="error.title"  :message="error.message" />
    
    <index v-if="error.title==''" v-on:evterror="on_error"/>
    
  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import api from "../providers/api"
import db from "../helpers/localdb"

import scrumbs from "@/components/navigation/scrumbs.vue"
import notierror from "@/components/common/notifications/notification_error.vue"
import index from "@/modules/iprequest/index.vue"

export default {

  name: "iprequest",
  
  components: {
    scrumbs,
    index,
    notierror,
  },

  data(){
    return {
      error:{ title:"", message:""}
    }
  },

  mounted: async function(){
    console.log("iprequest.mounted islogged",this.islogged)
  },

  beforeMount: async function (){
    console.log("iprequest.beforemount islogged",this.islogged)
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

    on_error(val){
      this.set_error("Error",val)
    },

  }
}
</script>