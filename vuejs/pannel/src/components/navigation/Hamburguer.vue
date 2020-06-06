<template>
  <v-navigation-drawer app
    v-model=navhamburguer
    absolute
    temporary
  >
    <v-list-item>
      <v-list-item-avatar>
        <v-img src="https://pbs.twimg.com/profile_images/1325817320/Calamardo_reasonably_small.gif"></v-img>
      </v-list-item-avatar>

      <v-list-item-content>
        <v-list-item-title>Eduardo A.F {{navhamburguer}}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>

    <v-divider></v-divider>

    <v-list dense>
      <v-list-item
        v-for="item in items"
        :key="item.title"
        link
      >
        <v-list-item-icon>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>

</template>

<script lang="ts">
import {mapState} from "vuex"

export default {

  name: "Hamburguer",

  data: () => ({
    drawer: true,
    items:[
      {
        title: "title A",
        icon: "mdi-cart-arrow-down"
      }
    ]
  }),

  mounted(){
    console.log("hamburg mounted")
  },

  computed:{
    ...mapState(["navhamburguer"])
  },


}
</script>