<template>
  <v-container>
    <scrumbs />
    <form-login/>
  </v-container>
</template>
<script>
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