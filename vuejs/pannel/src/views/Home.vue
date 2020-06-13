<template>
  <v-container>
    <scrumbs pagename="home" />
    <h1>IP Blocker</h1>
    
    <v-row>
      <v-col>
        <ul>
          <li>
          <a href="https://github.com/eacevedof/prj_ipblocker" class="black--text" target="_blank">En Github</a><br/>
          </li>
        </ul>
        <br/>
        <pre>
          IP Blocker es una herramienta que eventualmente está realizada en <b>PHP</b> y <b>Vue</b> y que tiene como fin
          poder gestionar las peticiones que se hacen a tus "dominios"
        </pre>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import Scrumbs from "@/components/navigation/Scrumbs.vue"
export default {
  name: "home",

  components:{
    Scrumbs,
  },
};
</script>
