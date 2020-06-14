<template>
  <h1>{{ name }}</h1>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
  name: 'aaa',
  
  props: {
    msg: String,
  },
});
</script>

<style>
</style>
