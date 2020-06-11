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
        v-for="item in get_links"
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

  name: "sidebar",

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
        href:"/ip-request"
      },
      {
        logged: true,
        title: "IP Blacklist",
        icon: "mdi-clipboard-text",
        href:"/ip-blacklist"
      },      
      {
        logged: true,
        title: "Log out",
        icon: "mdi-exit-run",
        href:"/logout"
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
    ...mapState(["islogged","sidebar"]),
    get_links(){
      if(this.islogged)
        return this.links.filter(link => link.logged)
      
      return this.links.filter(link => !link.logged)
      
    }

  },

  methods:{
 
  }
}
</script>