<template>
  <v-container>
    <scrumbs pagename="home" />
    <h1>IP Blocker</h1>
    <v-row>
      <v-col>
        <ul>
          <li
            v-for="(item, index) in ipsblacklisted"
            :key="index">
            {{item.description}}
          </li>
        </ul>
      </v-col>    
    </v-row>
  </v-container>
</template>

<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import Scrumbs from "@/components/navigation/Scrumbs.vue"
export default {
  name: "Home",

  components:{
    Scrumbs,
  },

  created(){
    //async_get_ipsblacklisted
    console.log("Home created")
    this.async_get_ipsblacklisted()
  },

  computed:{
    //las propiedades
    ...mapState(["pagetitle","ipsblacklisted"])
  },

  methods:{
    //los setters y getters 
    ...mapMutations(["set_sidebar"]),
    //ajax
    ...mapActions(["async_get_ipsblacklisted"])
  }    
};
</script>
