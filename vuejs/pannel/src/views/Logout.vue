<template>
  <div>loggin out</div>
</template>

<script lang="ts">
import {mapMutations} from "vuex"
import db from "../helpers/localdb"

export default {
  name: 'logout',

  mounted(){
    console.log("logout.beforeMount")
    this.set_islogged(false)
    db.delete("usertoken")
    this.$router.push({name:"login"})
  },

  methods:{
    ...mapMutations(["set_islogged"]),  
  }  
}
</script>