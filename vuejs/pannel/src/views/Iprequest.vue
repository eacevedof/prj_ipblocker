<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />
    <div class="mt-5"></div>

  
  </v-container>
</template>
<script lang="ts">
import Scrumbs from "@/components/navigation/Scrumbs.vue"
import api from "@/providers/api.ts"

export default {
  name: "IpRequest",

  components:{
    Scrumbs
  },

  beforemount: async function(){
    console.log("before")
  },
  

  components: {
    Scrumbs,
  },

};
</script>