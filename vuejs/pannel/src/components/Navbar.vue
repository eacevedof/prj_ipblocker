<template>
  <v-app-bar
    app
    color="primary"
    dark
  >
  
  <v-btn class="btn-edit"
      color="blue"
      :to="{ name: 'Home',}">
      Home
  </v-btn>

  <v-btn class="btn-edit"
      color="blue"
      :to="{ name: 'Login',}">
      Login
  </v-btn>  
  

    
  </v-app-bar>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  name: 'Navbar',
  

  props: {
    msg: String,
  },
});
</script>

<style>
</style>
