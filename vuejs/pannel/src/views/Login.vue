<template>
  <v-container>
    <h1>Login</h1>
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