<template>
  <v-navigation-drawer 
    app
    v-model="mdldrawer"
    absolute
    temporary
  >

    <v-list-item
      link
      :to="homehref"
    >
      <v-list-item-avatar>
        <v-img src="https://pbs.twimg.com/profile_images/1325817320/Calamardo_reasonably_small.gif"></v-img>
      </v-list-item-avatar>

      <v-list-item-content>
        <v-list-item-title>I'm watching you</v-list-item-title>
      </v-list-item-content>
    </v-list-item>

    <v-divider></v-divider>

    <v-list dense>

      <v-list-item
        v-for="item in links"
        :key="item.title"
        link
        :to="item.href"
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
import {mapMutations, mapState} from "vuex"

export default {

  name: "Sidebar",

  data: () => ({
    homehref: "/",
    links:[
      {
        logged: false,
        title: "Login",
        icon: "mdi-login",
        href:"/login"
      },
      {
        logged: true,
        title: "IP Requests",
        icon: "mdi-arrow-left-right",
        href:"/ip-requests"
      },      
    ]
  }),

  mounted(){
    console.log("sidebar mounted")
  },

  computed:{
    mdldrawer: {
        get() {
          console.log("mdldrawer.get.store.state.sidebar:",this.$store.state.sidebar)
          return this.$store.state.sidebar
        },

        set (isvisible) {
          console.log("mdldrawer.set.isvisible:",isvisible)
          this.$store.commit('set_sidebar',isvisible)
        }
      },
    ...mapState(["sidebar"]),
  },

  methods:{
    //...mapMutations(["set_sidebar"]),
  }
}
</script>