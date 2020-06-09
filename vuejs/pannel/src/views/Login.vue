<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="login" />
    <form-login/>
  
  </v-container>
</template>
<script lang="ts">
import Scrumbs from "@/components/navigation/Scrumbs.vue"
import FormLogin from "@/components/forms/FormLogin.vue"

export default {
  name: "Login",

  components: {
    Scrumbs,
    FormLogin,
  },

};
</script>