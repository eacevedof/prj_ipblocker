<template>
  <div>
    <v-menu
      v-model="is_visible"
      position-x="150"
      position-y="150"
      absolute
      offset-y
    >
      <v-list>
        <v-list-item
          v-for="(item, index) in items"
          :key="index"
          @click="item.evt"
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
  },

  created(){
    alert("created")
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
        this.$emit("update:isvisible",v)
      }
    }
  },

  methods:{
   
    clone(){
      alert("clone")
      this.$emit("evtclone")
    },
    update(){
      alert("update")
      this.$emit("evtupdate")
    },
    remove(){
      alert("remove")
      this.$emit("evtdelete")
    },
    detail(){
      alert("detail")
      this.$emit("evtdetail")
    },
  },

}
</script>
