<template>
<div>
  <noticonfirm :isvisible="isconfirm"   title="Confirm" message="Are you sure to continue?" v-on:evtoption="async_ban" />
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
                <p>{{objrowform.remote_ip}} - <b>User agent:</b>{{objrowform.user_agent}}</p>
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
            <v-row v-if="objrowform.inbl!=''">
              <v-col class="pa-0">
                <h4 :class="{'cyan--text':objrowform.inbl!=''}">In Blacklist</h4>
                <p>{{objrowform.inbl}}</p>            
              </v-col>             
              <v-col class="pa-0">
                <h4 :class="{'cyan--text':objrowform.inbl!=''}">Reason</h4>
                <p :class="{fontcode:objrowform.reason!=''}">{{objrowform.reason}}</p>                   
              </v-col>  
              <v-col class="pa-0">
                <h4 :class="{'cyan--text':objrowform.bl_date!=''}">Date in BL</h4>
                <span class="datered">{{objrowform.bl_date}}</span>
              </v-col>                                    
            </v-row>
            <v-row>
              <v-col class="pa-0">
                <h4>Domain</h4>
                <p>{{objrowform.domain}}</p>
              </v-col>             
              <v-col class="pa-0">
                <h4>Req. URI</h4>
                <p>{{objrowform.request_uri}}</p>
              </v-col>            
            </v-row>
            <v-row>
              <v-col class="pa-0">
                <h4>GET</h4>
                <p :class="{fontcode:objrowform.get!=''}">{{objrowform.get}}</p>
              </v-col>            
            </v-row> 
            <v-row>
              <v-col class="pa-0">
                <h4>POST</h4>
                <p :class="{fontcode:objrowform.post!=''}">{{objrowform.post}}</p>
              </v-col>            
            </v-row>                    
            <v-row>
              <v-col class="pa-0">
                <h4>Date</h4>
                <p class="ma-0">{{objrowform.insert_date}} | (now: {{moment().format('YYYY-MM-DD H:mm')}})</p>
              </v-col>
            </v-row>
            <!-- contadores -->
            <v-row>
              <v-col class="pa-0">
                <h5>Requests per sec (>2)</h5>
                <ul class="borderleft" >
                  <li v-for="(item,i) in requestsby.sec"
                      :key="i">
                    <span :class="[ (get_blockdate('sec')==item.d) ? 'datered': '']">{{item.d}}</span> - {{item.i}}
                  </li>
                </ul>
              </v-col>              
              <v-col class="pa-0">
                <h5>Requests per min (>2)</h5>
                <ul class="borderleft" >
                  <li v-for="(item,i) in requestsby.min"
                      :key="i">
                    <span :class="[ (get_blockdate('min')==item.d) ? 'datered': '']">{{item.d}}</span>  - {{item.i}}
                  </li>
                </ul>
              </v-col>
              <v-col class="pa-0">
                <h5>Requests per hour (>2)</h5>
                <ul class="borderleft" >
                  <li v-for="(item,i) in requestsby.hour"
                      :key="i">
                    <span :class="[ (get_blockdate('hour')==item.d) ? 'datered': '']">{{item.d}}</span>  - {{item.i}}
                  </li>
                </ul>
              </v-col>            
              <v-col class="pa-0">
                <h5>Requests per day</h5>
                <ul class="borderleft" >
                  <li v-for="(item,i) in requestsby.day"
                      :key="i">
                    <span :class="[ (get_blockdate('day')==item.d) ? 'datered': '']">{{item.d}}</span>  - {{item.i}}
                  </li>
                </ul>
              </v-col>          
            </v-row>          
            <v-row>
              <v-col class="pa-0">
                <h5>Requests from this ip</h5>
                <ul class="fontcode">
                  <li v-for="(item,i) in requestsby.ip"
                      :key="i">
                    <span :class="[ (get_blockdate('full')==item.insert_date) ? 'datered': '']">{{item.insert_date}}</span> {{item.domain}}{{item.requri}} |<b>g</b>:{{item.g}} |<b>p</b>:{{item.p}}
                  </li>
                </ul>            
              </v-col>
            </v-row>
            <progressbar :isvisible="issubmitting" />
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn color="blue-grey" :disabled="issubmitting" class="ma-2 white--text" @click="close">Close</v-btn>
          <v-btn color="cyan accent-4" :disabled="issubmitting" class="ma-2 cyan--text text--lighten-5" @click="async_refresh">Refressh</v-btn>
          <v-btn v-if="blockdate.full==null || blockdate.full==''" color="red accent-4" :disabled="issubmitting" class="ma-2 red--text text--lighten-5" @click="show_confirm">Ban</v-btn>
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

import {get_obj_entity, config} from "../../modules/iprequest/queries"
import {get_requests_by_ip, get_requests_per_day, get_requests_per_hour, get_into_blacklist, get_requests_per_min, get_requests_per_sec} from "../../modules/iprequest/queries_detail"
import get_filters from "../../helpers/filter"

import progressbar from "@/components/common/bars/progress_bar.vue"
import notisuccess from "@/components/common/notifications/notification_success.vue"
import notierror from "@/components/common/notifications/notification_error.vue"
import noticonfirm from "@/components/common/notifications/notification_confirm.vue"

export default {

  name: "iprequest-detail",

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
      error: {title:"",mesage:""},
      success: {title:"",message:""},
      objrowform: {},
      objflag: {},
      requestsby:{
        ip:[],
        day:[],
        hour:[],
        min:[],
        sec:[],
      },
      blockdate:{
        full: "",
      }
    }
  ),

  //getters
  computed:{
    
    get_dialogtitle(){return `Nº:${this.objrowform.id} - IP: ${this.objrowform.remote_ip}`},

    is_visible:{
      get(){ return this.isvisible},
      set(v){}
    },

    is_submitting(){return this.issubmitting},

  },
  
  created(){
    console.log("detail.creatd",this.objrow)
    this.objrowform = {...this.objrow}
    this.async_refresh()
  },

  watch:{
    isvisible: function(curr,old){
      if(curr==true){
        this.objrowform = {...this.objrow}
        this.async_refresh()
      }
      console.log("detail.watch.isvisible",this.objrowform)
    }
  },

  //setters 
  methods:{

    get_blockdate(format="full"){
      //pr(this.blockdate.full,"full")
      if(!format || format==="full") return this.blockdate.full
      if(format=="day") return (this.moment(this.blockdate.full).format("YYYY-MM-DD"))
      if(format=="hour") return (this.moment(this.blockdate.full).format("YYYY-MM-DD HH"))
      if(format=="min") return this.moment(this.blockdate.full).format("YYYY-MM-DD HH:mm")
      if(format=="sec") return this.moment(this.blockdate.full).format("YYYY-MM-DD HH:mm:ss")
      return this.moment().format("YYYYMMDD hmmss")
    },

    set_objrowform(objrow){
      this.objrowform = {...objrow}
      this.blockdate.full = this.objrowform.bl_date
    },

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

    async_refresh: async function (){
      this.reset_alerts()
      this.issubmitting = true
      
      const objparam = {
        filters:{
          op: "AND",
          fields:[{field:"r.id", value:this.objrowform.id}]
        }
      }

      const objquery = get_obj_entity(objparam)
      const result = await apidb.async_get_list(objquery)

      const objq1 = get_requests_by_ip(this.objrowform.remote_ip)
      const r1 = await apidb.async_get_list(objq1)
      this.requestsby.ip = r1.result

      const objq2 = get_requests_per_day(this.objrowform.remote_ip)
      const r2 = await apidb.async_get_list(objq2)
      this.requestsby.day = r2.result
    
      const objq3 = get_requests_per_hour(this.objrowform.remote_ip)
      const r3 = await apidb.async_get_list(objq3)
      this.requestsby.hour = r3.result

      const objq4 = get_requests_per_min(this.objrowform.remote_ip)
      const r4 = await apidb.async_get_list(objq4)
      this.requestsby.min = r4.result

      const objq5 = get_requests_per_sec(this.objrowform.remote_ip)
      const r5 = await apidb.async_get_list(objq5)
      this.requestsby.sec = r5.result

      //pr(r3,"R3")
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

    async_ban: async function (option){
      this.isconfirm = false
      //pr(option)
      if(option!=="accept") return
      
      const objq = get_into_blacklist({remote_ip:this.objrowform.remote_ip,reason:"manual suspicious"})
      const r = await apidb.async_insert(objq)
      this.async_refresh()
    }
  }
}
</script>
<style scoped>
p.fontcode {
  font-family: 'Lucida Console',courrier, monospace !important;
  font-size: 0.95em;
  border: 1px dashed #00BCD4;
}
ul.fontcode {
  font-family: 'Lucida Console',courrier, monospace !important;
  font-size: 0.80em;
  border: 1px solid #00BCD4;
}
ul.borderleft {
  font-family: 'Lucida Console',courrier, monospace !important;
  font-size: 0.80em;  
  border-left: 1px dashed #00BCD4;
}
span.datered {
  font-weight: bold;
  color:red;
}
</style>