<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="ipblacklist" />
    
    <index />
    
  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import scrumbs from "@/components/navigation/scrumbs.vue"
import index from "@/modules/ipblacklist/index.vue"

export default {

  name: "ipblacklist",
  
  components: {
    scrumbs,
    index,
  },

  beforeMount: async function (){
    console.log("ipblacklist beforeMount")
    await this.async_islogged()
    if(!this.islogged)
      this.$router.push({name:"login"})
  },

  mounted: async function(){
    console.log("ipblacklist mounted")
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