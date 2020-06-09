<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />
    <div class="mt-5"></div>

  
  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import Scrumbs from "@/components/navigation/Scrumbs.vue"
import api from "@/providers/api.ts"

export default {
  name: "IpRequest",
  
  components: {
    Scrumbs,
  },

  data: () => ({
    valid: true,

  }),//data

  beforeMount: async function(){
    //comprobar si se esta logado, si no está redirect a login
  },

  mounted: async function(){
    ;
  },

  computed:{
    ...mapState(["myip"])
  },

  methods:{
    ...mapMutations(["set_sidebar"]),
  }  

};
</script>