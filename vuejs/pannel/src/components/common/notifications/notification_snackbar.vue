<template>
  <div class="text-center ma-2">
    <v-snackbar v-model="is_showsnack">
      {{get_innertext}}
      <v-btn color="info" text>Cerrar</v-btn>
    </v-snackbar>
  </div>
</template>
<script lang="ts">

export default {

  name: "notification-snackbar",

  props:{
    showsnack:Boolean,
    innertext:String,
  },
  
  computed:{
    is_showsnack(){
      return this.showsnack
    },

    get_innertext(){
      return this.innertext
    },
  }
}
</script>