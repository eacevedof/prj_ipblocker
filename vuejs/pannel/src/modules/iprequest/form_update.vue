<template>
  <v-dialog v-model="is_visible" max-width="700px" persistent>
    <v-card>      
      <v-card-title class="yellow accent-4 blue-grey-darken-2--text">
        <span class="headline">
          <v-icon color="black">mdi-pencil</v-icon> <b>Editing IP Request:</b> {{get_dialogtitle}}
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
              <v-text-field v-model="objrowform.remote_ip" readonly label="R. IP" />
            </v-col>
            <v-col ms="1">
              <v-text-field v-model="objrowform.country" readonly label="Country" />
            </v-col>
            <v-col sm="6">
              <v-text-field v-model="objrowform.domain" label="Domain" />
            </v-col>
          </v-row>
          <v-row>
            <v-col sm="12">
              <v-text-field v-model="objrowform.whois" readonly label="Whois" />
            </v-col>                        
          </v-row>
          <v-row>
            <v-col>  
              <v-text-field v-model="objrowform.request_uri" label="Uri" />
              <v-textarea rows="1" v-model="objrowform.get" :value="objrowform.get" label="GET" />
            </v-col>
            <v-col>
              <v-textarea rows="1" v-model="objrowform.post" :value="objrowform.post" label="POST" />
              <v-text-field v-model="objrowform.insert_date" readonly label="Date" />
            </v-col>
          </v-row>
          <progressbar :isvisible="issubmitting" />
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="blue-grey" :disabled="issubmitting" class="ma-2 white--text" @click="close">Close</v-btn>
        <v-btn color="yellow accent-4" :disabled="issubmitting" class="ma-2 blue-grey-darken-2--tex" @click="async_save">Save</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>
<script lang="ts">
import {pr} from "../../helpers/functions"
import apidb from "../../providers/apidb"
import {get_obj_update, table} from "../../modules/iprequest/queries"

import progressbar from "@/components/common/bars/progress_bar.vue"
import notisuccess from "@/components/common/notifications/notification_success.vue"
import notierror from "@/components/common/notifications/notification_error.vue"

export default {

  name: "iprequest-formupdate",

  components:{
    progressbar,
    notisuccess,
    notierror,
  },

  props:{
    isvisible: Boolean,
    objrow: Object,
  },

  data: ()=>(
    {
      issubmitting: false,
      error:{title:"",mesage:""},
      success:{title:"",message:""},
      objrowform: {}
    }
  ),

  created(){
    this.objrowform = {...this.objrow}
  },

  watch: {
    isvisible: function(curr,old){
      if(curr){
        this.objrowform = {...this.objrow}
      }
    }
  },

  //getters
  computed:{
    
    get_dialogtitle(){
      return `Nº:${this.objrowform.id} - IP: ${this.objrowform.remote_ip}`
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
    },

    async_save: async function (){
      //this.loader = 'loading5'
      this.reset_alerts()
      this.issubmitting = true
      
      //alert(JSON.stringify(this.objrow))
      const objparam = {
        fields: this.objrowform,
        keys:["id"]
      }

      const tblconf = await apidb.async_get_fields(table)
      const objquery = get_obj_update(objparam, tblconf)
      const result = await apidb.async_update(objquery)
      
      this.issubmitting = false
      if(result.error){  
        this.set_error("Error",result.error)        
        this.$emit("evtupdate","nok")
        return
      }
      
      this.set_success("Success",`Reults updated ${result}`)
      this.$emit("evtupdate","ok")
    }// async
  }
}
</script>