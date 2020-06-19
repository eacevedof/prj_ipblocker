<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="set_sidebar(true)"></v-app-bar-nav-icon>

    <v-toolbar-title>
      <b>IP Blocker </b> 
    </v-toolbar-title>
    <v-spacer />
    <v-toolbar-items >
      <v-row align="center">
        <v-col cols="3" class="d-flex justify-end">
          <sub> <b>{{ myip }}</b></sub>
        </v-col>
      </v-row>
    </v-toolbar-items>
  </v-app-bar>
  
</template>

<script lang="ts">
import Vue from 'vue';
import {mapMutations, mapActions, mapState} from "vuex"

export default Vue.extend({

  name: "navbar",  
  data(){
    return {

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
