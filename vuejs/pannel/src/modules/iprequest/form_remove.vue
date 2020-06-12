<template>
  <v-row justify="center">
    
    <v-dialog v-model="is_visible" persistent max-width="290">

      <v-card>
        <v-card-title class="headline">Removing data</v-card-title>
        <v-card-text>
          Are you sure to remove register {{objrow.id}} related to IP: {{objrow.remote_ip}}
          created at {{objrow.insert_date}}<br/>
          <b>This operation is irreversible</b>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary darken-1" text @click="accept">Cancel</v-btn>
          <v-btn color="error darken-1" text @click="cancel">Accept</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script lang="ts">
export default {

  props:{
    isvisible: Boolean,
    objrow:{},
  },

  computed:{
    is_visible:{
      get(){
        return this.isvisible
      },
      set(val){
        this.$emit("evtclose",val)
      }
    }
  },

  methods:{
    accept(){
      this.$emit("evtremove","ok")
    },

    cancel(){
      this.is_visible = false
    }
  },
}
</script>