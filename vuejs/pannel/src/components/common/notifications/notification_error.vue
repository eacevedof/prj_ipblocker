<template>
  <v-alert
    color="#C51162"
    dark
    icon="mdi-material-design"
    border="right"
  >
    <b>{{tittle}}</b> <p>{{message}}</p>
  </v-alert>
</template>

<script lang="ts">
export default {
  name: 'NotificationError',
  props: {
    tittle: String,
    message: String
  },
}
</script>