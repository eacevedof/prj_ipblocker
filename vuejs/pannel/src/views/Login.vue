<template>
  <v-container>
    <h2>Access</h2>
    <form-login/>
  </v-container>
</template>
<script>
import FormLogin from "@/components/forms/FormLogin.vue"
export default {
  name: "Login",

  components: {
    FormLogin
  },

};
</script>