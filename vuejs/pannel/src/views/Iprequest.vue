<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />
    
    <div class="mt-5">
      {{ islogged }}
    </div>
  
  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import Scrumbs from "@/components/navigation/Scrumbs.vue"
//import api from "@/providers/api.ts"

export default {
  name: "IpRequest",
  
  components: {
    Scrumbs,
  },

  data: () => ({

  }),//data

  beforeMount: async function(){
    
    await this.async_islogged()
    if(!this.islogged)
      this.$router.push({name:"login"})
    //comprobar si se esta logado, si no está redirect a login
  },

  mounted: async function(){
    ;
  },

  computed:{
    ...mapState(["islogged"])
  },

  methods:{
    //setters
    ...mapActions(["async_islogged"]),
  }  

};
</script>