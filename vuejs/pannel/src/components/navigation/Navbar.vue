<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="set_sidebar(true)"></v-app-bar-nav-icon>

    <v-toolbar-title >IP Blocker - Your ip: {{ myip }}</v-toolbar-title>
    
    <v-spacer />
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
    ...mapState(["myip"])
  },

  methods:{
    ...mapMutations(["set_sidebar"]),
  }  


})
</script>
