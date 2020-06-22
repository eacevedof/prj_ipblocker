<template>
  <v-dialog v-model="is_visible" max-width="700px" persistent>
    <v-card>
      <v-card-title class="teal accent-4 blue-grey-darken-2--text">
        <span class="headline">
          <v-icon color="black">mdi-plus-circle</v-icon> <b>Clonning IP Request</b> {{get_dialogtitle}}
          </span>
      </v-card-title>

       <v-card-text>
        <v-container>
          <v-form ref="form"
          >
            <v-row v-if="error.title!='' || success.title!=''">
              <v-col ms="12">
                <notierror v-if="error.title!=''" :title="error.title"  :message="error.message" />
                <notisuccess v-if="success.title!=''" :title="success.title"  :message="success.message" />
              </v-col>
            </v-row>
            <v-row>
              <v-col>  
                <v-text-field v-model="objrowform.id" disabled label="Nº" />
              </v-col>              
              <v-col>  
                <v-text-field v-model="objrowform.remote_ip" disabled label="Rem. IP" />
              </v-col>
              <v-col>
                <v-text-field v-model="objrowform.domain" disabled label="Domain" />
              </v-col>
              <v-col>
                <v-text-field v-model="objrowform.request_uri" disabled label="Uri" />
              </v-col>
            </v-row>
            <v-row>
              <v-col>  
                <v-textarea rows="1" v-model="objrowform.get" disabled label="GET" />              
                <v-textarea rows="1" v-model="objrowform.post" disabled label="POST" />              
              </v-col>
            </v-row>
            <progressbar :isvisible="issubmitting" />
          </v-form>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="blue-grey" :disabled="issubmitting" class="ma-2 white--text" @click="close">Close</v-btn>
        <v-btn color="teal accent-4" :disabled="issubmitting" class="ma-2 blue-grey-darken-2--tex" @click="async_save">Accept</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>
<script lang="ts">
import apidb from "../../providers/apidb"
import {pr} from "../../helpers/functions"
import {get_obj_clone, get_obj_entity, table} from "../../modules/ipblacklist/queries"

import progressbar from "@/components/common/bars/progress_bar.vue"
import notisuccess from "@/components/common/notifications/notification_success.vue"
import notierror from "@/components/common/notifications/notification_error.vue"


export default {

  name: "ipblacklist-formclone",

  components:{
    progressbar,
    notisuccess,
    notierror,
  },

  props:{
    //si se muestra el form
    isvisible: Boolean, 
    objrow: Object,
  },

  data: ()=>(
    {
      issubmitting: false,
      error:{title:"",mesage:""},
      success:{title:"",message:""},
      objrowform: {},
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
    
    get_dialogtitle(){return ``},
    
    is_submitting(){return this.issubmitting},

    is_visible:{
      get(){
        return this.isvisible
      },
      set(val){
        //lanza un evento hacia afuera
        this.$emit("evtclose",val)
      }
    },

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

    get_query(idval){
            
      const objparam = {
        filters:{
          op: "AND",
          fields:[{field:"r.id", value:idval}]
        }
      }

      return get_obj_entity(objparam)
    },

    async_save: async function (){
      this.reset_alerts()
      this.issubmitting = true
      
      let objparam = {
        fields: this.objrow
      }
      
      const dbfields = await apidb.async_get_fields(table)
      //pr(dbfields,"dbfields"); return
      let objquery = get_obj_clone(objparam, dbfields)
      let result = await apidb.async_insert(objquery)
      
      this.issubmitting = false
      if(result.error){  
        this.set_error("Error",result.error)        
        this.$emit("evtclone","nok")
        return
      }
      
      this.set_success("Success",`Reg created ${result}`)
      this.$emit("evtclone","ok")

      objquery = this.get_query(result)
      result = await apidb.async_get_list(objquery)
      this.objrowform = result.result[0]
      
    }// async
  }
}
</script>