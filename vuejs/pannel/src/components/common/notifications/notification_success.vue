<template>
  <v-alert
    color="success"
    dark
    icon="mdi-check-circle-outline"
    border="right"
    dense
  >
    <b>{{title}} </b> {{message}}
  </v-alert>
</template>

<script lang="ts">
export default {
  name: 'notificationsuccess',
  props: {
    title: String,
    message: String
  },
}
</script>