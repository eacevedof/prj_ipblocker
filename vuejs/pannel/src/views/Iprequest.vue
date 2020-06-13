<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />
    
    <list />
    
  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import api from "../providers/api"
import db from "../helpers/localdb"

import Scrumbs from "@/components/navigation/Scrumbs.vue"
import list from "@/modules/iprequest/list.vue"

export default {

  name: "iprequest",
  
  components: {
    Scrumbs,
    list,
  },

  beforeMount: async function (){
    console.log("iprequest beforeMount")
    await this.async_islogged()
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
  }
}
</script>