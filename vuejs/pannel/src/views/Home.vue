<template>
  <v-container>
    <h1>Lorem ipsum dolor sit.</h1>
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

<script>

import {mapMutations, mapActions, mapState} from "vuex"
export default {
  name: "Home",

  created(){
    //get_customers
    console.log("Home created")
    this.get_customers()
  },

  computed:{
    ...mapState(["globalx","customers"])
  },

  methods:{
    ...mapMutations(["set_navhamburger"]),
    //ajax
    ...mapActions(["get_customers"])
  }    
};
</script>
