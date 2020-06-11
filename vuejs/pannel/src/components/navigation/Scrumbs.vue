<template>
  <v-breadcrumbs :items="get_scrumbs">
    <template v-slot:item="{ item }">
      <v-breadcrumbs-item
        :href="item.href"
        :disabled="item.disabled"
      >
        {{ item.text.toUpperCase() }}
      </v-breadcrumbs-item>
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
          disabled: true,
          href: '/',
        },
      ],
      "login":[
        {
          text: 'Home',
          disabled: false,
          href: '/',
        },
        {
          text: 'Login',
          disabled: true,
          href: '/login',
        },
      ],
      "iprequest":[
        {
          text: 'Home',
          disabled: false,
          href: '/',
        },
        {
          text: 'Ip Requests',
          disabled: true,
          href: '/ip-request',
        },
      ],  
      "ipblacklist":[
        {
          text: 'Home',
          disabled: false,
          href: '/',
        },
        {
          text: 'IP Blacklist',
          disabled: true,
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
  }

}
</script>