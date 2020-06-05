<template>
  <v-toolbar app class="primary">

    <v-app-bar-nav-icon @click="$store.commit('set_navhamburger')"></v-app-bar-nav-icon>

    <v-app-bar-side-icon @click="$store.commit('set_globalx',{value:'xxxxx'})"></v-app-bar-side-icon>

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

export default Vue.extend({

  name: "Navbar",  

  components: {

  },

  data () {
    return {
      
    }
  },
})
</script>
