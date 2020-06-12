<template>
  <v-dialog v-model="showstate" max-width="700px">
    
    <v-card>
      
      <v-card-title class="yellow accent-4 blue-grey-darken-2--text">
        <span class="headline"><b>Editing:</b> {{get_dialogtitle}}</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row v-if="error.title!='' || success.title!=''">
            <v-col ms="12">
              <notierror v-if="error.title!=''" :title="error.title"  :message="error.message" />
              <notisuccess v-if="success.title!=''" :title="success.title"  :message="success.message" />
            </v-col>
          </v-row>
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
          <progressbar :isvisible="issubmitting" />
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="blue-grey" :disabled="issubmitting" class="ma-2 white--text" @click="close">Close</v-btn>
        <v-btn color="teal accent-4" :disabled="issubmitting" class="ma-2 white--text" @click="async_save">Save</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>
<script lang="ts">
import progressbar from "@/components/common/bars/progress_bar.vue"
import notisuccess from "@/components/common/notifications/notification_success.vue"
import notierror from "@/components/common/notifications/notification_error.vue"
import api from "../../providers/api"
export default {

  name: "form-edit",

  components:{
    progressbar,
    notisuccess,
    notierror,
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
      issubmitting: false,
      error:{title:"",mesage:""},
      success:{title:"",message:""}
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

    clearalert(){
      this.error = {title:"",message:""}
      this.success = {title:"",message:""}
    },

    close(){
      this.clearalert()
      this.showstate = false
    },

    async_save: async function (){
      //this.loader = 'loading5'
      this.clearalert()
      this.issubmitting = true
      
      const result = await api.async_update(this.objrow, ["id"])
      
      this.issubmitting = false
      if(result.error){  
        this.error.title = "Error"
        this.error.message = "Some error ocurred. " + result.error
        
        this.$emit("evtresult","error on async_save")
        return
      }
        
      this.success.title = "Success"
      this.success.message = "Data has been saved!"
      //this.showstate = true  
      //this.$emit("evtresult","all fine :)")
    }
  }
}
</script>