<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />

    <div class="mt-5">
      {{ islogged }}
    </div>
  
  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import Scrumbs from "@/components/navigation/Scrumbs.vue"
import api from "@/providers/api.ts"
import db from "@/helpers/localdb.ts"

export default {
  name: "IpRequest",
  
  components: {
    Scrumbs,
  },

  data: () => ({
    rows: []
  }),//data

  beforeMount: function(){
    ;
    //comprobar si se esta logado, si no está redirect a login
  },

  mounted: function(){
    this.load_data()
  },

  computed:{
    ...mapState(["islogged"])
  },

  methods:{
    //setters
    ...mapActions(["async_islogged"]),

    load_data: async function(){
      await this.async_islogged()
      if(!this.islogged)
        this.$router.push({name:"login"})

      console.log("iprequest.mounted.islogged: ",this.islogged)
      if(this.islogged){
        const usertoken = db.select("usertoken")
        if(!usertoken) return
        this.rows = await api.async_get_ip_request(usertoken)
        console.table(this.rows)
      }
    }

  }  

};
</script>