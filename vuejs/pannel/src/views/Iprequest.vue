<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />
    <list />
    

  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import api from "../providers/api"
import db from "../helpers/localdb"

import Scrumbs from "@/components/navigation/Scrumbs.vue"
import list from "@/modules/iprequest/list.vue"

export default {
  name: "IpRequest",
  
  components: {
    Scrumbs,
    list,
  },

  data: () => ({
  
  }),//data

  beforeMount: function(){
    ;
    //comprobar si se esta logado, si no está redirect a login
  },

  mounted: function(){
    this.load_data()
  },

  created(){;},

  computed:{
    ...mapState(["islogged"]),
  },

  watch:{},

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
        const response = await api.async_get_ip_request(usertoken)
        this.rows = response.result
        this.foundrows = response.foundrows
        //alert(this.foundrows)
        //alert(JSON.stringify(response));
        //console.log("THIS.ROWS: ",this.foundrows)
        console.table(this.rows)
      }
    },
  }

};
</script>