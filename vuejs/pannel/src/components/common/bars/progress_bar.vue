<template>
  <v-progress-linear
      v-if="visible"
      :value="progress"
      :color="color"
      absolute
      height="7"
  ></v-progress-linear>
</template>

<script>
export default {
  
  name: "progressbar",

  props:{
    visible:Boolean
  },

  data: () => ({
    value: '',
  }),
  
  computed: {
    progress () {
      return Math.min(100, this.value.length * 10)
    },
    color () {
      return ['error', 'warning', 'success'][Math.floor(this.progress / 40)]
    },
  },

}
</script>