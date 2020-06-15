<template>
  <div>
    <v-menu
      v-model="isvisible"
      :position-x="x"
      :position-y="y"
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

  watch:{
    isvisible: function(e){
      alert("iiissssvvv")
      e.preventDefault()
      this.showMenu = false
      this.x = e.clientX
      this.y = e.clientY
      this.$nextTick(() => {
        this.showMenu = true
      })
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
