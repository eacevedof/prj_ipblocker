<template>
  <v-container>
    <scrumbs pagename="home" />
    <h1>IP Blocker</h1>
    <v-row>
      <v-col>
        <ul>
          <li
            v-for="(item, index) in customers"
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
    //get_customers
    console.log("Home created")
    this.get_customers()
  },

  computed:{
    //las propiedades
    ...mapState(["globalx","customers"])
  },

  methods:{
    //los setters y getters 
    ...mapMutations(["set_sidebar"]),
    //ajax
    ...mapActions(["get_customers"])
  }    
};
</script>
