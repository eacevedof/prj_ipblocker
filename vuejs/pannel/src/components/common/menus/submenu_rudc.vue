<template>
  <div>
    <v-menu
      v-model="is_visible"
      :position-x="evtclick.x || x"
      :position-y="evtclick.y || y"
      absolute
      offset-y
    >
      <v-list>
        <v-list-item
          v-for="(item, index) in items"
          :key="index"
          @click="clickhandler(item.evt)"
        >
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script lang="ts">
export default {
  name: "submenu-rudc",

  props:{
    isvisible: Boolean,
    evtclick: {},
  },

  created(){
    console.log("evtclick",this.evtclick)
  },

  data: () => ({
    x:0,
    y:0,
    items: [
      { title: 'Detail', evt:"detail" },
      //{ title: 'Clone', evt:"clone" },
      //{ title: 'Update', evt:"update" },
      //{ title: 'Delete', evt:"delete"},
    ],
  }),

  computed:{
    is_visible:{
      get(){
        return this.isvisible
      },
      set(v){
        this.$emit("evtclose",v)
      }
    }
  },

  methods:{
    clickhandler(fncname){
      this.$emit("evtselected",fncname)
    },
  },

}
</script>
