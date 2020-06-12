<template>
  <v-row
    class="fill-height"
    align-content="center"
    justify="center"
  >
    <v-col cols="6">
      <v-progress-linear
        color="deep-purple accent-4"
        indeterminate
        rounded
        height="6"
      ></v-progress-linear>
    </v-col>
  </v-row>
</template>

<script>
export default {
  
  name: "progressbar",

  props:{
    visible:Boolean
  },

}
</script>