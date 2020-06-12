<template>
  <v-dialog v-model="showstate" max-width="700px">
    <template v-slot:activator="{}"></template>
    <v-card>
      
      <v-card-title class="yellow accent-4 blue-grey-darken-2--text">
        <span class="headline"><b>Editing:</b> {{get_dialogtitle}}</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row>
            <v-col ms="5">
              <v-text-field v-model="objrow.remote_ip" label="R. IP" />
            </v-col>
            <v-col ms="1">
              <v-text-field v-model="objrow.country" label="Country" />
            </v-col>
            <v-col sm="6">
              <v-text-field v-model="objrow.domain" label="Domain" />
            </v-col>
          </v-row>
          <v-row>
            <v-col sm="12">
              <v-text-field v-model="objrow.whois" label="Whois" />
            </v-col>                        
          </v-row>
          <v-row>
            <v-col>  
              <v-text-field v-model="objrow.request_uri" label="Uri" />
              <v-textarea rows="1" v-model="objrow.get" label="GET" />
            </v-col>
            <v-col>
              <v-textarea rows="1" v-model="objrow.post" label="POST" />
              <v-text-field v-model="objrow.insert_date" readonly label="Date" />
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

    objrow: {
      id:         "",
      remote_ip:  "",
      country:    "",
      whois:      "",
      domain:     "",
      request_uri:"",
      get:        "",
      post:       "",
      insert_date:"",    
    },

  },

  //getters
  computed:{
    
    get_dialogtitle(){
      return `Nº:${this.objrow.id} - IP: ${this.objrow.remote_ip}`
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
      const objrow = {...this.objrow}

    }
  }
}
</script>