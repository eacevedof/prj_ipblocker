<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="ipblacklist" />
    
    <list />
    
  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import api from "../providers/api"
import db from "../helpers/localdb"

import scrumbs from "@/components/navigation/scrumbs.vue"
import list from "@/modules/iprequest/list.vue"

export default {

  name: "ipblacklist",
  
  components: {
    scrumbs,
    list,
  },

  beforeMount: async function (){
    console.log("ipblacklist beforeMount")
    await this.async_islogged()
    if(!this.islogged)
      this.$router.push({name:"login"})
  },

  mounted: async function(){
    console.log("ipblacklist mounted")
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