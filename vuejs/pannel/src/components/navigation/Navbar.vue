<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="set_sidebar(true)"></v-app-bar-nav-icon>

    <v-toolbar-title>
      <b>IP Blocker </b> 
    </v-toolbar-title>
    <v-spacer />
    <v-toolbar-items >
      <v-col cols="6" class="d-flex align-center justify-start">
        <sub>Your ip:{{ myip }}</sub>
      </v-col>
      <v-col cols="7" class="d-flex align-center justify-end">
        <sub>{{ get_now }}</sub>
      </v-col>
    </v-toolbar-items>
  </v-app-bar>
  
</template>

<script lang="ts">
import Vue from 'vue';
import {mapMutations, mapActions, mapState} from "vuex"

export default Vue.extend({

  name: "navbar",  
  data(){
    return {
      ithread: -1,
      timeout: "",
    }
  },

  computed:{
    //state
    ...mapState(["myip"]),
    get_now(){
      this.set_now()
      return this.timeout      
    }
  },

  methods:{
    //setters
    ...mapMutations(["set_sidebar"]),
    set_now(){
      if(this.ithread == -1){
        const now = this.moment().format('YYYY-MM-DD H:m:s')
        this.ithread = setInterval(()=>{
          this.timeout = now
        },1000)
      }
      console.log("set_now.this.ithread",this.ithread)
    }
  }

})
</script>
