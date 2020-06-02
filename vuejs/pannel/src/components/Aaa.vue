<template>
  <h1>{{ name }}</h1>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  name: 'Aaaa',
  

  props: {
    msg: String,
  },
});
</script>

<style>
</style>
