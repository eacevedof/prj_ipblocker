<template>
  <v-progress-linear
    v-if="isvisible"
    color="teal accent-3"
    indeterminate
    striped
    height="6"
  ></v-progress-linear>
</template>

<script>
export default {
  name: "progressbar",

  props:{
    isvisible:Boolean
  },

}
</script>