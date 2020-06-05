<template>
  <v-toolbar app class="primary">

    <v-app-bar-nav-icon></v-app-bar-nav-icon>

    <v-toolbar-title >IP Blocker {{$store.state.globalx}}</v-toolbar-title>
    
    <v-spacer />
    <v-btn class="success">
      login
    </v-btn>
    <v-btn class="error">
      salir
    </v-btn>

  </v-toolbar>

</template>

<script lang="ts">
import Vue from 'vue';
//import Hamburguer from "@/components/navigation/Hamburguer.vue"

export default Vue.extend({

  name: "Navbar",  

  components: {
    //Hamburguer,
  },

  data () {
    return {
      
    }
  },
})
</script>
