<template>
<div>
  <noticonfirm :isvisible="isconfirm"   title="Confirm" message="Are you sure to continue?" v-on:evtoption="async_unban" />
  <v-dialog v-model="is_visible" max-width="900px" persistent>
    
    <v-card>
      <v-card-title class="cyan accent-4 cyan--text text--lighten-5">
        <span class="headline">
          <v-icon color="">mdi-eye-outline</v-icon> <b>Detail of IP Request:</b> {{get_dialogtitle}}
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
            <v-col class="pa-0">
              <h4>Remote IP</h4>
              <p>{{objrowform.remote_ip}}</p>
            </v-col>
            <v-col class="pa-0">
              <h4>Country</h4>
              <p>{{objrowform.country}} {{objflag.name}}</p>
              <v-img v-if="objflag.name" :src="objflag.flag" max-height="55" max-width="80" />
            </v-col>
            <v-col class="pa-0">
              <h4>Whois</h4>
              <p>{{objrowform.whois}}</p>
            </v-col>
          </v-row>
          <v-row v-if="objrowform.is_blocked=='1'">
            <v-col class="pa-0">
              <h4 :class="{'red--text':objrowform.is_blocked=='1'}">In Blacklist</h4>
              <p>{{objrowform.is_blocked}}</p>            
            </v-col>             
            <v-col class="pa-0">
              <h4 :class="{'red--text':objrowform.is_blocked=='1'}">Reason</h4>
              <p :class="{fontcode:objrowform.reason!=''}">{{objrowform.reason}}</p>                   
            </v-col>  
            <v-col class="pa-0">
              <h4 :class="{'red--text':objrowform.insert_date!=''}">Date in BL</h4>
              <p>{{objrowform.insert_date}}</p>                   
            </v-col>
          </v-row>
          <progressbar :isvisible="issubmitting" />
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn color="blue-grey" :disabled="issubmitting" class="ma-2 white--text" @click="close">Close</v-btn>
        <v-btn color="cyan accent-4" :disabled="issubmitting" class="ma-2 cyan--text text--lighten-5" @click="async_detail">Refressh</v-btn>
        <v-btn v-if="blockdate.full==null || blockdate.full==''" color="green accent-4" :disabled="issubmitting" class="ma-2 green--text text--lighten-5" @click="show_confirm">Unban</v-btn>        
      </v-card-actions>

    </v-card>
  </v-dialog>
</div>
</template>
<script lang="ts">
import {pr} from "../../helpers/functions"
import apidb from "../../providers/apidb"
import apiip from "../../providers/apiip"
import apiflag from "../../providers/apiflag"

import {get_obj_entity, config, get_obj_detete} from "../../modules/ipblacklist/queries"
import get_filters from "../../helpers/filter"

import progressbar from "@/components/common/bars/progress_bar.vue"
import notisuccess from "@/components/common/notifications/notification_success.vue"
import notierror from "@/components/common/notifications/notification_error.vue"
import noticonfirm from "@/components/common/notifications/notification_confirm.vue"

export default {

  name: "ipblacklist-detail",

  components:{
    progressbar,
    notisuccess,
    notierror,
    noticonfirm,
  },

  props:{
    //si se muestra el form
    isvisible: Boolean,
    objrow: Object,
  },

  data: ()=>(
    {
      isconfirm: false,
      issubmitting: false,
      error:{title:"",mesage:""},
      success:{title:"",message:""},
      objrowform:{},
      objflag:{},
      blockdate:{
        full: "",
      },
    }
  ),

  //getters
  computed:{
    
    get_dialogtitle(){return `Nº:${this.objrowform.id} - IP: ${this.objrowform.remote_ip}`},

    is_visible:{
      get(){return this.isvisible},
      set(v){}
    },

    is_submitting(){
      return this.issubmitting
    },

  },
  
  created(){
    console.log("detail.creatd",this.objrow)
    this.objrowform = {...this.objrow}
    this.async_detail()
  },

  watch:{
    isvisible: function(curr,old){
      if(curr==true){
        this.objrowform = {...this.objrow}
      }
      console.log("detail.watch.isvisible",this.objrowform)
    }
  },

  //setters 
  methods:{

    set_objrowform(objrow){ this.objrowform = {...objrow}},

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
      console.log("detail.close.close",this.objrowform)
      this.objrowform = {}
      this.objflag = {}
      this.reset_alerts()
      this.$emit("evtclose")
    },
    show_confirm(){
      this.isconfirm = true
    },
    async_detail: async function (){
      this.reset_alerts()
      this.issubmitting = true
      
      const objparam = {
        filters:{
          op: "AND",
          fields:[{field:"bl.id", value:this.objrowform.id}]
        }
      }
      const objquery = get_obj_entity(objparam)
      const result = await apidb.async_get_list(objquery)
      //alert(JSON.stringify(this.objrowform))
      const flag = await apiflag.async_getflags([this.objrowform])
      this.objflag = {...flag[0]}

      this.issubmitting = false
      if(result.error){  
        this.set_error("Error",result.error)        
        this.$emit("evtrefresh","nok")
        return
      }

      if(!result.result[0]){  
        this.set_error("Error","No result")        
        this.$emit("evtrefresh","nok")
        return
      }      
      
      this.set_objrowform(result.result[0])
      this.set_success("Success",`Reg refreshed ${this.objrowform.id}`)
      this.$emit("evtrefresh","ok")
    },// async

    async_unban: async function (option){
      this.isconfirm = false
      //pr(option)
      if(option!=="accept") return
      this.issubmitting = true
      
      const objparam={
        fields: this.objrowform,
        keys:["id"]
      }
      
      const objquery = get_obj_detete(objparam)
      const result = await apidb.async_delete(objquery)
      this.$emit("evtdelete","ok") //refresca el grid
      this.$emit("evtclose","ok") //cierra el form
      this.issubmitting = false
      await this.async_refresh()

    }// async_unban

  }//methods

}//vue
</script>
<style scoped>
p.fontcode {
  font-family: 'Lucida Console',courrier, monospace !important;
  font-size: 0.85em;
  border: 1px dashed red;
}
</style>