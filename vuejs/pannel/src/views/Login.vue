<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="login" />
    <div class="mt-5"></div>
    <form-login />
  
  </v-container>
</template>
<script lang="ts">
import Scrumbs from "@/components/navigation/Scrumbs.vue"
import FormLogin from "@/components/forms/FormLogin.vue"

export default {
  name: "login",

  components: {
    Scrumbs,
    FormLogin,
  },

};
</script>