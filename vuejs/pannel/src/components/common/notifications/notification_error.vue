<template>
  <v-alert
    color="error"
    dark
    icon="mdi-alert-circle"
    border="right"
    dense
  >
    <b>{{title}} </b> {{message}}
  </v-alert>
</template>

<script lang="ts">
export default {
  name: 'notificationerror',
  props: {
    title: String,
    message: String
  },
}
</script>