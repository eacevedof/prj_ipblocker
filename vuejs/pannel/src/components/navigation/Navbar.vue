<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="set_sidebar(true)"></v-app-bar-nav-icon>

    <v-toolbar-title>
      <b>IP Blocker </b> 
    </v-toolbar-title>
    
    <v-spacer />

    <v-toolbar-items>
      <v-tabs fixed-tabs color="secondary" background-color="primary">
        <v-tab>
          <span class="subtitle-2">Your ip:{{ myip }}</span>
        </v-tab>
        <v-tab>
          <span class="subtitle-2">{{moment(date).format('YYYY-MM-DD H:m:s')}}</span>
        </v-tab>
      </v-tabs>
    </v-toolbar-items>

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
