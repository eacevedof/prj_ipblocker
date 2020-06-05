<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="$store.commit('set_navhamburger')"></v-app-bar-nav-icon>



    <v-toolbar-title >IP Blocker {{ show_globalx }}</v-toolbar-title>
    
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

export default Vue.extend({

  name: "Navbar",  

  components: {

  },

  data () {
    return {
      
    }
  },

  computed:{
    show_globalx(): string {
      return this.$store.state.globalx
    }
  }
})
</script>
