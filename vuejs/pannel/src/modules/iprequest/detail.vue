<template>
  <h1>{{ list }}</h1>
</template>

<script lang="ts">
export default {
  name: 'iprequest-detail',
  
  props: {
    objrow: {
      id:         "",
      remote_ip:  "",
      country:    "",
      whois:      "",
      domain:     "",
      request_uri:"",
      get:        "",
      post:       "",
      insert_date:"",
    }
  },
}
</script>

<style>
</style>
