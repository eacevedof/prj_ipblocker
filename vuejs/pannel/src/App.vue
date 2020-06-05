<template>
  <v-app>
    <navbar />
    <hamburguer />
    <v-content>
      <router-view />
    </v-content>
  </v-app>
</template>

<script lang="ts">
import Vue from 'vue';
import Navbar from "@/components/navigation/Navbar.vue"
import hamburguer from "@/components/navigation/Hamburguer.vue"

export default Vue.extend({
  name: 'App',

  components: {
    Navbar,
    hamburguer
  },

  data: () => ({
    //
  }),
});
</script>
