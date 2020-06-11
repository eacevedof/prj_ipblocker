<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="set_sidebar(true)"></v-app-bar-nav-icon>

    <v-toolbar-title>
      <b>IP Blocker </b> 
    </v-toolbar-title>
    <v-spacer />
    <v-toolbar-items >
      <v-col cols="6" class="d-flex align-center justify-start">
        <sub>Your ip:{{ myip }}</sub>
      </v-col>
      <v-col cols="7" class="d-flex align-center justify-end">
        <sub>{{moment().format('YYYY-MM-DD H:m:s')}}</sub>
      </v-col>
    </v-toolbar-items>
  </v-app-bar>
  
</template>

<script lang="ts">
import Vue from 'vue';
import {mapMutations, mapActions, mapState} from "vuex"

export default Vue.extend({

  name: "Navbar",  

  computed:{
    //state
    ...mapState(["myip"])
  },

  methods:{
    //setters
    ...mapMutations(["set_sidebar"]),
  }  


})
</script>
