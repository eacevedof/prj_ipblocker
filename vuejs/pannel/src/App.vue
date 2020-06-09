<template>
  <v-app>
    
    <navbar />

    <sidebar />

    <v-content>
      <router-view />
    </v-content>

  </v-app>
</template>

<script lang="ts">
import Vue from 'vue';
import Navbar from "@/components/navigation/Navbar.vue"
import Sidebar from "@/components/navigation/Sidebar.vue"
import {mapMutations, mapActions, mapState} from "vuex"

export default Vue.extend({
  name: 'App',

  created(){
    this.get_async_myip()
  },

  components: {
    Navbar,
    Sidebar
  },

  data: () => ({
    //
  }),
  

  computed:{
    //las propiedades
    ...mapState(["myip"])
  },

  methods:{
    //ajax
    ...mapActions(["get_async_myip"])
  } 

});
</script>
