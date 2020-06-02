<template>
  <v-bottom-navigation
    v-model="bottomNav"
    color="yellow"
    dark
  >
    <v-btn :to="{name:'home'}">
      <span>Home</span>
      <v-icon>mdi-home</v-icon>
    </v-btn>

    <v-btn :to="{name:'login'}">
      <span>Login</span>
      <v-icon>mdi-login</v-icon>
    </v-btn>

  </v-bottom-navigation>
</template>

<script lang="ts">
export default {
  name: "Navbar",  
  data () {
    return {
      bottomNav: 'recent',
    }
  },
}
</script>
