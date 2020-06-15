<template>
  <div>
    <v-menu
      v-model="is_visible"
      :position-x="evtclick.x"
      :position-y="evtclick.y"
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
    showMenu: false,
    x:0,
    y:0,
    items: [
      { title: 'Clone', evt:"clone" },
      { title: 'Update', evt:"update" },
      { title: 'Delete', evt:"remove"},
      { title: 'Detail', evt:"detail" },
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
      this.$emit(fncname)
    },
  },

}
</script>
