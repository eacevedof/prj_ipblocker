<template>
  <v-dialog v-model="is_visible" max-width="700px">
    <v-card>    
      <v-card-title class="red accent-4 white--text">
        <span class="headline"> 
          <v-icon color="white">mdi-trash-can-outline</v-icon> <b>Deleting IP Request!:</b> {{get_dialogtitle}}
        </span>
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
              <v-text-field v-model="objrow.remote_ip" label="R. IP" disabled outlined dense />
            </v-col>
            <v-col ms="1">
              <v-text-field v-model="objrow.country" disabled outlined dense label="Country" />
            </v-col>
            <v-col sm="6">
              <v-text-field v-model="objrow.domain" disabled outlined dense label="Domain" />
            </v-col>
          </v-row>
          <v-row>
            <v-col sm="12">
              <v-text-field v-model="objrow.whois" disabled outlined dense label="Whois" />
            </v-col>                        
          </v-row>
          <v-row>
            <v-col>  
              <v-text-field v-model="objrow.request_uri" label="Uri" disabled outlined dense />
              <v-textarea rows="1" v-model="objrow.get" label="GET" disabled outlined dense />
            </v-col>
            <v-col>
              <v-textarea rows="1" v-model="objrow.post" label="POST" disabled outlined dense />
              <v-text-field v-model="objrow.insert_date" disabled label="Date" outlined dense />
            </v-col>
          </v-row>
          <progressbar :isvisible="issubmitting" />
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="blue-grey" :disabled="issubmitting" class="ma-2 white--text" @click="close">Close</v-btn>
        <v-btn v-if="isaccept" color="red accent-4" :disabled="issubmitting" class="ma-2 white--text" @click="async_accept">Accept</v-btn>
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

  name: "iprequest-formdelete",

  components:{
    progressbar,
    notisuccess,
    notierror,
  },

  props:{
    //si se muestra el form
    isvisible: Boolean,
    objrow: {},
  },

  data: ()=>(
    {
      issubmitting: false,
      error:{title:"",mesage:""},
      success:{title:"",message:""},
      isaccept: true,
    }
  ),

  watch: {

  },

  //getters
  computed:{
    
    get_dialogtitle(){
      return `Nº:${this.objrow.id} - IP: ${this.objrow.remote_ip}`
    },

    is_visible:{
      get(){
        return this.isvisible
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

    set_error(title,message){
      this.error.title = title
      this.error.message = message
    },

    set_success(title,message){
      this.success.title = title
      this.success.message = message
    },    

    reset_alerts(){
      this.set_error("","")
      this.set_success("","")
    },

    close(){
      this.reset_alerts()
      this.is_visible = false
      this.isaccept = true
    },

    async_accept: async function(){
      this.reset_alerts()
      this.issubmitting = true

      const result = await api.async_delete(this.objrow,["id"])
      this.issubmitting = false
      
      if(result.error){  
        this.set_error("Error",result.error)        
        this.$emit("evtdelete","nok")
        return
      }
      
      this.set_success("Success",`Reults deleted ${result}`)
      this.isaccept = false
      this.$emit("evtdelete","ok")
    },
  },
}
</script>