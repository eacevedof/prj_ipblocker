<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="set_navhamburger"></v-app-bar-nav-icon>

    <v-toolbar-title >IP Blocker {{ globalx }}</v-toolbar-title>
    
    <v-spacer />

    <v-btn class="success">
      login
    </v-btn>
    <v-btn class="error">
      salir
    </v-btn>
  </v-app-bar>
  
</template>

<script lang="ts">
import Vue from 'vue';
import {mapMutations, mapActions, mapState} from "vuex"

export default Vue.extend({

  name: "Navbar",  

  components: {

  },

  data () {
    return {
      
    }
  },

  computed:{
    ...mapState(["globalx","customers"])
  },

  methods:{
    ...mapMutations(["set_navhamburger"]),
    ...mapActions(["get_customers"])
  }  


})
</script>
