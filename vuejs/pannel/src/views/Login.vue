<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="login" />
    
    <div class="mt-5"></div>

    <formlogin />
  
  </v-container>
</template>
<script lang="ts">
import scrumbs from "@/components/navigation/scrumbs.vue"
import formlogin from "@/modules/login/form_login.vue"

export default {
  name: "login",

  components: {
    scrumbs,
    formlogin,
  },

};
</script>