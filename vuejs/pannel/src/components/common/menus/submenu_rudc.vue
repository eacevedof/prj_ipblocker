<template>
  <v-row>
    <v-col cols="12" sm="6" offset-sm="3">
      <v-card height="200px">
        <v-card-title class="blue white--text">
          <span class="headline">Menu</span>

          <v-spacer></v-spacer>

          <v-menu 
            v-model="isvisible"
            bottom left
          >


            <v-list>
              <v-list-item
                v-for="(item, i) in items"
                :key="i"
                @click="items.evt"
              >
                <v-list-item-title>{{ item.title }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-card-title>

        <v-card-text>Lorem Ipsum</v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "submenu-rudc",

  props:{
    isvisible: Boolean,
  },

  data: () => ({
    items: [
      { title: 'Clone', evt:"clone" },
      { title: 'Update', evt:"update" },
      { title: 'Delete', evt:"remove"},
      { title: 'Detail', evt:"detail" },
    ],
  }),

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
