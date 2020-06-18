<template>
  <v-dialog v-model="is_visible" max-width="700px" style="z-index:99999;" persistent>
    <v-card>
      <v-card-title class="teal accent-4 blue-grey-darken-2--text">
        <span class="headline">
          <v-icon color="black">mdi-plus-circle</v-icon> <b>Clonning IP Request</b> {{get_dialogtitle}}
          </span>
      </v-card-title>

       <v-card-text>
        <v-container>
          <v-form ref="form"
            v-model="isformvalid"
            lazy-validation
          >
            <v-row v-if="error.title!='' || success.title!=''">
              <v-col ms="12">
                <notierror v-if="error.title!=''" :title="error.title"  :message="error.message" />
                <notisuccess v-if="success.title!=''" :title="success.title"  :message="success.message" />
              </v-col>
            </v-row>
            <v-row>
              <v-col>  
                <v-text-field v-model="objrow.remote_ip" disabled label="Rem. IP" />
              </v-col>
              <v-col>
                <v-text-field v-model="objrow.domain" disabled label="Domain" />
              </v-col>
              <v-col>
                <v-text-field v-model="objrow.request_uri" disabled label="Uri" />
              </v-col>
            </v-row>
            <v-row>
              <v-col>  
                <v-textarea rows="1" v-model="objrow.get" disabled label="GET" />              
                <v-textarea rows="1" v-model="objrow.post" disabled label="POST" />              
              </v-col>
            </v-row>
            <progressbar :isvisible="issubmitting" />
          </v-form>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="blue-grey" :disabled="issubmitting" class="ma-2 white--text" @click="close">Close</v-btn>
        <v-btn color="teal accent-4" :disabled="issubmitting" class="ma-2 blue-grey-darken-2--tex" @click="async_save">Save</v-btn>
      </v-card-actions>

    </v-card>
  </v-dialog>
</template>
<script lang="ts">
import apidb from "../../providers/apidb"
import {get_obj_insert} from "../../modules/iprequest/queries"

import progressbar from "@/components/common/bars/progress_bar.vue"
import notisuccess from "@/components/common/notifications/notification_success.vue"
import notierror from "@/components/common/notifications/notification_error.vue"


export default {

  name: "iprequest-formclone",

  components:{
    progressbar,
    notisuccess,
    notierror,
  },

  props:{
    //si se muestra el form
    isvisible: Boolean, 
  },

  data: ()=>(
    {
      issubmitting: false,
      error:{title:"",mesage:""},
      success:{title:"",message:""},

      objrow: {
        request_uri: "",
        domain: "",
        get: "",
        post: "",
      },



    }
  ),

  watch: {

  },
  //getters
  computed:{
    
    get_dialogtitle(){
      return ``
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

    reset_objrow(){
      this.objrow = {
        request_uri: "",
        domain: "",
        get: "",
        post: "",        
      }
    },

    close(){
      this.reset_alerts()
      this.is_visible = false
    },

    async_save: async function (){
      this.$refs.form.validate()

      //this.loader = 'loading5'
      this.reset_alerts()
      this.issubmitting = true
      
      const objparam = {
        fields: this.objrow
      }
      
      const objquery = get_obj_insert(objparam)
      const result = await apidb.async_insert(objquery)
      
      this.issubmitting = false
      if(result.error){  
        this.set_error("Error",result.error)        
        this.$emit("evtclone","nok")
        return
      }
      
      this.set_success("Success",`Reg created ${result}`)
      this.$emit("evtclone","ok")
      this.reset_objrow()
      this.$refs.form.resetValidation()
    }// async
  }
}
</script>