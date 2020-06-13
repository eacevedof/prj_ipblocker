<template>
  <v-breadcrumbs :items="get_scrumbs">
    <template v-slot:item="{ item }">
      <v-breadcrumbs-item
        :to="item.href"
      >
        {{ item.text.toUpperCase() }}
      </v-breadcrumbs-item>
      <span v-if="item.iscurrent" style="margin-left:5px; margin-right:10px;">
        <v-btn x-small icon @click="refresh">
          <v-icon>mdi-cached</v-icon>
        </v-btn>
      </span>      
    </template>
  </v-breadcrumbs>
</template>

<script lang="ts">
export default {

  props:{
    pagename: String
  },

  data: () => ({
    obscrumbs: {
      "home":[
        {
          text: 'Home',
          iscurrent: true,
          href: '/',
        },
        {
          text: 'Login',
          iscurrent: false,
          href: '/login',
        },        
      ],
      "login":[
        {
          text: 'Home',
          iscurrent: false,
          href: '/',
        },
        {
          text: 'Login',
          iscurrent: true,
          href: '/login',
        },
      ],
      "iprequest":[
        {
          text: 'Home',
          iscurrent: false,
          href: '/',
        },
        {
          text: 'Ip Requests',
          iscurrent: true,
          href: '/ip-request',
        },
        {
          text: 'IP Blacklist',
          iscurrent: false,
          href: '/ip-blacklist',
        },        
      ],  
      "ipblacklist":[
        {
          text: 'Home',
          iscurrent: false,
          href: '/',
        },
        {
          text: 'Ip Requests',
          iscurrent: false,
          href: '/ip-request',
        },        
        {
          text: 'IP Blacklist',
          iscurrent: true,
          href: '/ip-blacklist',
        },
      ]  
    }
  }),
  
  computed:{
    get_scrumbs(){
      const pagename = this.pagename
      if(this.obscrumbs[pagename] == undefined)
        return []
      return this.obscrumbs[pagename]
    }
  },

  methods:{
    refresh(){
      //this.$forceUpdate();
      //this.$router.push({ name: pathname})
      //alert("refresh")
      //this.$router.go(0)
      //location.reload()
      this.$router.go(this.$router.currentRoute)
    },
  }

}
</script>