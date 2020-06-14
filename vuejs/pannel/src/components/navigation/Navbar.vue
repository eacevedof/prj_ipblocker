<template>
  <v-app-bar app class="primary">

    <v-app-bar-nav-icon @click="set_sidebar(true)"></v-app-bar-nav-icon>

    <v-toolbar-title>
      <b>IP Blocker </b> 
    </v-toolbar-title>
    <v-spacer />
    <v-toolbar-items >
      <v-row align="center">
        <v-col cols="3" class="d-flex justify-start">
          <sub>Your ip:</sub>
        </v-col>
        <v-col cols="3" class="d-flex justify-end">
          <sub> <b>{{ myip }}</b></sub>
        </v-col>
        <v-col cols="6" class="d-flex justify-start">
          <sub>{{ get_now }}</sub>
        </v-col>
      </v-row>
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
      this.ithread = setInterval(()=>{
        this.timeout = this.moment().format('YYYY-MM-DD H:m:ss')
        //console.log("navbar.set_now clearing interval: ",this.ithread)
        clearInterval(this.ithread)
      },1000)
      //console.log("navbar.set_now.this.ithread",this.ithread)
    }
  }

})
</script>
