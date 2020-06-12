<template>
  <v-snackbar
    v-model="isvisible"
  >
    {{ innertext }}
    <template v-slot:action="{ attrs }">
      <v-btn
        color="pink"
        text
        v-bind="attrs"
        @click="on_click"
      >
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>
<script lang="ts">
export default {
  name: "notification-snackbar",
  props:{
    isvisible: Boolean,
    innertext: String,
  },

  methdos:{
    on_click(){
      this.$emit("evtclose",1)
    }
  }

}
</script>