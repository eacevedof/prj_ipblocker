<template>
  <!-- https://github.com/vuetifyjs/vuetify/blob/master/packages/docs/src/examples/dialogs/intermediate/loader.vue-->
  <v-row justify="center">
    <v-col cols="1">
      <v-dialog
        v-model="isvisible"
        hide-overlay
        persistent
        width="300"
      >
        <v-card
          color="primary"
          dark
        >
          <v-card-text>
            <span class="blue-grey--text">Please stand by </span>
            <v-progress-linear
              indeterminate
              color="blue-grey darken-4"
              class="mb-0"
            />
          </v-card-text>
        </v-card>
        
      </v-dialog>
    </v-col>
  </v-row>
</template>
<script lang="ts">
export default {
  name: "progressbarover",

  props:{
    isvisible:Boolean
  },

}
</script>