<template>
  <div>
    <barover :isvisible="isfetching" />

    <submenu :isvisible="issubmenu" :evtclick="evtsubmenu" v-on:evtselected="submenu_selected" v-on:evtclose="issubmenu=false" />

    <v-row>
      <v-col
        cols="10"
        md="10"
      >
        <v-text-field
          v-model="dbsearch"
          label="Search"
          append-icon="mdi-text-box-search-outline"
          outlined
        ></v-text-field>
      </v-col>
    </v-row>


    <div v-if="page.ipages>1" class="text-center pt-2">
      <v-pagination 
        v-model="page.ipage" 
        :page="page.ipage"
        :length="page.ipages" 
        @input="on_pagechange"
        total-visible="10"
      />
    </div>
    
    <v-data-table v-if="!isfetching" 
      :headers="headers" 
      :items="arrows"
      :items-per-page="page.ippage"
      hide-default-footer

      :search="search" 

      class="elevation-3"
    >
      <!-- inyecta en la cabecera de la tabla en la zona top-->
      <template v-slot:top>
        
        <v-system-bar color="yellow lighten-1" />

        <v-toolbar color="black darken-4">
          
          <v-btn
            class="mx-2"
            :elevation="10"
            fab dark color="light-green accent-4"
            @click="insert" 
          ><v-icon>mdi-plus</v-icon></v-btn>

          <v-divider class="mx-4" inset vertical/>
          <v-toolbar-title class="yellow--text font-weight-bold">IP Request</v-toolbar-title>
          <v-spacer></v-spacer>

          <!-- los hijos se comunican por eventos con los padres -->
          <forminsert v-if="crudopt=='insert'" :isvisible="showform" v-on:evtinsert="dialog_result" v-on:evtclose="showform=false" />
          <detail v-if="crudopt=='detail'" :objrow="objrow" :isvisible="showform" v-on:evtclose="showform=false" />
          <formupdate v-if="crudopt=='update'" :objrow="objrow" :isvisible="showform" v-on:evtupdate="dialog_result" v-on:evtclose="showform=false" />
          <formdelete v-if="crudopt=='delete'" :objrow="objrow" :isvisible="showform" v-on:evtdelete="dialog_result" v-on:evtclose="showform=false" />

        </v-toolbar>

        <!-- barra busqueda -->
        <v-col>
          <v-text-field v-model="search" append-icon="search" label="Search in page result" single-line hide-details />
        </v-col>

      </template>
      <!-- fin cabecer tabla -->


      <!-- botones columna -->
      <template v-slot:item.colbuttons="{ item }">
        <v-btn class="m4-2" fab dark small color="cyan" @click="submenu(item)"><v-icon dark>mdi-dots-vertical</v-icon></v-btn>
        <!--
        <v-btn class="m4-2" fab dark small color="cyan" @click="update(item)"><v-icon dark>mdi-pencil</v-icon></v-btn>
        <v-btn class="m4-2" fab dark small color="error" @click="remove(item)"><v-icon dark>mdi-delete</v-icon></v-btn>
        -->
      </template>

      <!--
      <notifsnack :showsnack="showsnack" :innertext="textsnack" v-on:evtclose="showsnack=false" />
      -->
    </v-data-table>
    <div v-if="page.ipages>1" class="text-center pt-2">
      <v-pagination 
        v-model="page.ipage" 
        :page="page.ipage"
        :length="page.ipages" 
        @input="on_pagechange"
        total-visible="10"
      />
    </div>
  </div>
</template>

<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import {pr} from "../../helpers/functions"
import apidb from "../../providers/apidb"
import url from "../../helpers/url"
import {get_obj_list} from "../../modules/iprequest/queries"

import notifsnack from "@/components/common/notifications/notification_snackbar.vue"
import barover from "@/components/common/bars/progress_barover.vue"
import submenu from "@/components/common/menus/submenu_rudc.vue"
import detail from "@/modules/iprequest/detail.vue"
import forminsert from "@/modules/iprequest/form_insert.vue"
import formupdate from "@/modules/iprequest/form_update.vue"
import formdelete from "@/modules/iprequest/form_delete.vue"



export default {
  name: "iprequest-index",
  
  components: {
    //notifsnack,
    submenu,
    forminsert,
    detail,
    formupdate,
    formdelete,
    barover,
  },

  data: () => ({

    issubmenu: false,
    evtsubmenu: {x:0,y:0},

    isfetching: false,
    showform: false,
    crudopt: "",

    search: "",
    
    objrow: {},

    headers: [
      {
        text: 'nº',
        align: 'start',
        sortable: true,
        value: 'id',
      },
      { text: 'Action', value: 'colbuttons' },
      { text: 'Rem. IP', value: 'remote_ip' },
      { text: 'Country', value: 'country' },
      { text: 'Whois', value: 'whois' },
      { text: 'Domain', value: 'domain' },
      { text: 'R. URI', value: 'request_uri' },
      { text: 'GET', value: 'hasget' },
      { text: 'POST', value: 'haspost' },
      { text: 'In BL', value: 'inbl' },
      { text: 'Day', value: 'insert_date' },
    ],
    arrows: [],

    page:{
      ipage: 1,     //pagina actual
      ippage: 100,  //regs por pag
      ipages: 0,    //num de paginas
      foundrows:0,  //total de registros
    },

    dbsearch:"",
    debdbsearch: "",

  }),//data

  watch:{
    dbsearch(val) {
      if (!val) {
        return;
      }

      this.on_dbsearch(val);
    }
  },

  //no puede ser asincrono pq el ciclo de vida no aplica await
  beforeMount(){
    url.route = this.$route
    console.log("iprequest.index.beforemount url.route",url.route)
  },


  //lo marco como async pq tengo que resolver peticiones
  mounted: async function(){

    console.log("iprequest.index.mounted.islogged antes de llamar a async_islogged",this.islogged)
    //async_islogged comprueba el token en local si existe comprueba si es válido
    //si pasa estos filtros this.islogged tendría el valor true
    const response = await this.async_islogged()
    console.log("iprequest.index.mounted.islogged",this.islogged)

    if(!this.islogged){
      this.$router.push({name:"login"})
      return
    }

    //error network
    if(response.error){
      this.$emit("evterror",response.error)
      return 
    }

    console.log("iprequest.index.mounted islogged:",this.islogged)
    await this.async_loaddata()
  },

  computed:{
    ...mapState(["islogged"]),
  },

  methods:{
    //setters
    ...mapActions(["async_islogged"]),

    async_loaddata_: async function(page=null, filters=[]){
      this.isfetching = true

      const ipage = parseInt(page) || this.get_page()
      this.page.ipage = ipage
      const ippage = this.page.ippage
      const ifrom = (ipage-1) * ippage
      const objpage = {ippage,ifrom}
      //console.log("async_loaddata.router.params",url.get_param("page"))
      //console.log("async_loaddata.router.query",url.get_get("ip"))
      const response = await apidb.async_get_ip_request(objpage,null,filters)
      this.arrows = response.result
      this.page.foundrows = response.foundrows
      //this.page.ipages = Math.ceil(this.arrows.length/ippage)
      this.page.ipages = Math.ceil(this.page.foundrows/ippage)
      //alert(this.page.ipages)

      console.table("iprequest.index.async_loaddata.page.count:",this.page.foundrows)
      this.isfetching = false
    },

    async_loaddata: async function(objparam={page:null,filters:{}}){
      this.isfetching = true

      const ipage = parseInt(objparam.page) || this.get_page()
      this.page.ipage = ipage
      const ippage = this.page.ippage
      const ifrom = (ipage-1) * ippage
      const objpage = {ippage,ifrom}

      const obinput =  {page:ipage,filters:objparam.filters}
      const objquery = get_obj_list(obinput)
      //console.log("async_loaddata.router.params",url.get_param("page"))
      //console.log("async_loaddata.router.query",url.get_get("ip"))
      const response = await apidb.async_get_ip_request(objquery)
      pr(response,"response")
      return
      this.arrows = response.result
      this.page.foundrows = response.foundrows
      //this.page.ipages = Math.ceil(this.arrows.length/ippage)
      this.page.ipages = Math.ceil(this.page.foundrows/ippage)
      //alert(this.page.ipages)

      console.table("iprequest.index.async_loaddata.page.count:",this.page.foundrows)
      this.isfetching = false
    },    

    show_form(){
      this.showform = true
    },

    dialog_result(val){
      //alert("dialog_result: "+val)
      const ipage = url.get_param("page")
      this.async_loaddata({page:ipage,filters:{}})
    },

    insert(){
      //alert(JSON.stringify("insert"))
      this.crudopt = "insert"
      //set showform=true
      this.show_form()
    },

    update(objrow){
      //alert(JSON.stringify(objrow))
      this.crudopt = "update"
      this.objrow = objrow
      //set showform=true
      this.show_form()
    },

    remove(objrow){
      this.crudopt = "delete"
      this.objrow = objrow
      this.show_form()
    },

    submenu(objrow){
      const e = window.event
      this.objrow = objrow
      this.issubmenu = true
      this.evtsubmenu = e
    },

    submenu_selected(option){
      this.crudopt = option
      this.show_form()
    },

    get_page(){
      const ipage = url.get_param("page") || 1
      //alert(ipage)
      if(isNaN(ipage)) return 1
      return parseInt(ipage)
    },

    on_pagechange(ipage){
      //alert("changepage"+JSON.stringify(ipage))
      this.async_loaddata({page:ipage,filters:{}})
      //alert(this.$route.path)
      if(this.$route.path !== `/ip-request/${ipage}`)
        this.$router.push({ name: 'iprequest', params: { page: ipage } })
    },

    on_dbsearch(text) {
      // cancel pending call
      clearTimeout(this.debounceid);

      this.issearching = true;
      // delay new call 500ms
      this.debounceid = setTimeout(() => {
        const ipage = this.get_page()
        text = text.trim()
        if(text!=="")
          this.async_loaddata({page:ipage,filters:{country:`LIKE '%${text}%'`}})
        else
          this.async_loaddata({page:ipage,filters:{}})
        this.issearching = false
      }, 1000);
    },
    
  }//methods  

};
</script>
