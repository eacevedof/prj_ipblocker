<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="login" />
    
    <div class="mt-5"></div>

    <formlogin />
  
  </v-container>
</template>
<script lang="ts">
import Scrumbs from "@/components/navigation/Scrumbs.vue"
import formlogin from "@/modules/login/form_login.vue"

export default {
  name: "login",

  components: {
    Scrumbs,
    formlogin,
  },

};
</script>