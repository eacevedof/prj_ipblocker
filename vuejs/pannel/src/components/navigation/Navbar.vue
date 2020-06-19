<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="set_sidebar(true)"></v-app-bar-nav-icon>

    <v-toolbar-title>
      <b>IP Blocker </b> 
    </v-toolbar-title>
    <v-spacer />
    <v-toolbar-items >
      <v-row align="center">
        <sub> <b>{{ myip }}</b> | {{version}}</sub>
      </v-row>
    </v-toolbar-items>
  </v-app-bar>
  
</template>

<script lang="ts">
import Vue from 'vue';
import {APP_VERSION} from "../../config/constants"
import {mapMutations, mapActions, mapState} from "vuex"

export default Vue.extend({

  name: "navbar",  
  data(){
    return {
      version: APP_VERSION
    }
  },


  computed:{
    //state
    ...mapState(["myip"]),
  },

  methods:{
    //setters
    ...mapMutations(["set_sidebar"]),
  }

})
</script>
