<template>
  <v-dialog v-model="showstate" max-width="500px">
    <template v-slot:activator="{}"></template>
    <v-card>
      
      <v-card-title class="cyan white-text">
        <span class="headline">Editing: {{get_dialogtitle}}</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12" sm="6" md="4">
              <v-text-field v-model="objrow.remote_ip" label="R. IP" />
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="blue-grey" class="ma-2 white--text" @click="cancel">Cancel</v-btn>
        <v-btn color="teal accent-4" class="ma-2 white--text" @click="save">Save</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>
<script lang="ts">

export default {

  name: "form-edit",

  props:{
    
    ison: Boolean,
    dialogtitle: String,

    objrow: {
      id: "",
      remote_ip: "",
      domain: ""
    }
  },
  
  data(){
    return {

    }
  },

  //getters
  computed:{
    
    get_dialogtitle(){
      return this.dialogtitle
    },

    showstate:{
      get(){
        return this.ison
      },
      set(val){
        //lanza un evento hacia afuera
        this.$emit("evtclose",val)
      }
    }

  },
  
  //setters 
  methods:{
    cancel(){
      //ejecuta el shostate.set
      this.showstate = false
    },
    save(){
      alert("saving")
    }
  }
}
</script>