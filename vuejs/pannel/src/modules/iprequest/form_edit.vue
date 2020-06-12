<template>
  <v-dialog v-model="showstate" max-width="700px">
    
    <v-card>
      
      <v-card-title class="yellow accent-4 blue-grey-darken-2--text">
        <span class="headline"><b>Editing:</b> {{get_dialogtitle}}</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row>
            <v-col ms="5">
              <v-text-field v-model="objrow.remote_ip" readonly label="R. IP" />
            </v-col>
            <v-col ms="1">
              <v-text-field v-model="objrow.country" readonly label="Country" />
            </v-col>
            <v-col sm="6">
              <v-text-field v-model="objrow.domain" label="Domain" />
            </v-col>
          </v-row>
          <v-row>
            <v-col sm="12">
              <v-text-field v-model="objrow.whois" readonly label="Whois" />
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
        <progressbar :visible="issubmitting" />
        <v-spacer />
        <v-btn color="blue-grey" :disabled="issubmitting" class="ma-2 white--text" @click="cancel">Cancel</v-btn>
        <v-btn color="teal accent-4" :disabled="issubmitting" class="ma-2 white--text" @click="async_save">Save</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>
<script lang="ts">
import progressbar from "@/components/common/bars/progress_bar.vue"
import api from "../../providers/api"
export default {

  name: "form-edit",

  components:{
    progressbar,
  },

  props:{

    ison: Boolean,

    objrow: {
      id:         "",
      remote_ip:  "",
      country:    "", //ro
      whois:      "", //ro
      domain:     "", 
      request_uri:"",
      get:        "",
      post:       "",
      insert_date:"",    
    },

  },

  data: ()=>(
    {
      //loader: null,
      //butloading: false,
      issubmitting: false,
    }
  ),

  watch: {

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
    },

    is_submitting(){
      return this.issubmitting
    }

  },
  
  //setters 
  methods:{

    cancel(){
      //ejecuta el shostate.set
      this.showstate = false
    },

    async_save: async function (){
      //this.loader = 'loading5'
      this.issubmitting = true
      const objrow = {...this.objrow}
      console.log("form_edit.methods.async_save.objrow",objrow)
      const result = await api.async_update(objrow, ["id"])
      
      this.issubmitting = false
      if(result.error){  
        this.showstate = false
        this.$emit("evtresult","error on async_save")
        return
      }
        
      this.showstate = true  
      this.$emit("evtresult","all fine :)")
    }
  }
}
</script>