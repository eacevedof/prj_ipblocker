<template>
  <div>
    <barover :isvisible="isfetching" />

    <submenu :isvisible="issubmenu" :evtclick="evtsubmenu" v-on:evtselected="submenu_selected" v-on:evtclose="issubmenu=false" />

<!-- los hijos se comunican por eventos con los padres -->
    <forminsert v-if="crudopt=='insert'" :isvisible="showform" v-on:evtinsert="form_result" v-on:evtclose="showform=false" />
    <detail v-if="crudopt=='detail'" :objrow="objrow" :isvisible="showform" v-on:evtclose="showform=false" />
    <formupdate v-if="crudopt=='update'" :objrow="objrow" :isvisible="showform" v-on:evtupdate="form_result" v-on:evtclose="showform=false" />
    <formdelete v-if="crudopt=='delete'" :objrow="objrow" :isvisible="showform" v-on:evtdelete="form_result" v-on:evtclose="showform=false" />

    <v-row
      justify="center" align="center"
    >
      <v-col
        cols="8"
        md="8"
      >
        <v-text-field
          ref="dbsearch"
          v-model="dbsearch"
          label="Search"
          append-icon="mdi-text-box-search-outline"
          outlined
          hide-details="true"
          color="secondary"
        />
        <sub>regs:{{page.foundrows}}</sub>
      </v-col>
    </v-row>

    
    <div v-if="page.ipages>1" class="text-center pt-2">
      <v-pagination 
        v-model="page.ipage" 
        :page="page.ipage"
        :length="page.ipages" 
        @input="on_paginate"
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
          <v-toolbar-title class="yellow--text font-weight-bold">
            IP Request
          </v-toolbar-title>
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
        @input="on_paginate"
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
import get_filters from "../../helpers/filter"
import {get_obj_list, config, grid} from "../../modules/iprequest/queries"


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

    headers: grid.headers,
    arrows: [],

    page:{
      ipage: 1,     //pagina actual
      ippage: 50,  //regs por pag
      ipages: 0,    //num de paginas
      foundrows:0,  //total de registros
    },

    dbsearch:"",

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
    console.log("iprequest.index.beforeMount")
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
    const objpage = this.get_page()

    this.page.ipage = objpage.ipage
    const objparam = {
      page: {...objpage},
      filters: {}
    }

    //pr(objparam,"objparam in async")
    await this.async_loaddata(objparam)
  },

  computed:{
    ...mapState(["islogged"]),
  },

  methods:{
    //setters
    ...mapActions(["async_islogged"]),

    get_page(){
      const ipage = parseInt(url.get_param("page",this.$route)) || 1
      const ippage = this.page.ippage
      const ifrom = (ipage-1) * ippage

      return {
        ipage: ipage,
        ippage: ippage,
        ifrom: ifrom,
      }
    },
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
    async_loaddata: async function(objparam={page:{},filters:{}}){
      this.isfetching = true
      const objquery = get_obj_list(objparam)
      //pr(objquery,"objquery")
      const response = await apidb.async_get_list(objquery)
      //pr(response,"response");return
      this.arrows = response.result
      this.page.ipage = objparam.page.ipage
      this.page.foundrows = response.foundrows
      this.page.ipages = Math.ceil(this.page.foundrows/objparam.page.ippage)
      //alert(this.page.ipages)
      //filter(this.dbsearch,config)

      console.table("iprequest.index.async_loaddata.page.foundrows:",this.page.foundrows)
      this.isfetching = false
      this.$refs.dbsearch.focus()

    }, //async_loaddata
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
    on_dbsearch(text) {
      // cancel pending call
      clearTimeout(this.debounceid);

      this.issearching = true;
      // delay new call 500ms
      this.debounceid = setTimeout(() => {
        text = text.trim()
        if(this.$route.path !== `/ip-request/1`){
          this.$router.push({ name: 'iprequest', params: { page: 1 } })      
        }
      
        const objpage = this.get_page()

        this.page.ipage = objpage.ipage
        const objparam = {
          page: {...objpage},
          filters: {}
        }

        if(text!==""){
          objparam.filters = get_filters(text, config)
          //pr(objparam,"objparam")
          this.async_loaddata(objparam)
        }
        else
          this.async_loaddata(objparam)
        
        this.issearching = false
      }, 1000);

    },   

    on_paginate(ipage){
      if(this.$route.path !== `/ip-request/${ipage}`)
        this.$router.push({ name: 'iprequest', params: { page: ipage } })

      const objpage = this.get_page()
      //pr(objpage,`ipage: ${ipage}`)

      this.page.ipage = objpage.ipage
      const objparam = {
        page: {...objpage},
        filters: {}
      }      
      if(this.dbsearch)
        objparam.filters = get_filters(this.dbsearch, config)

      this.async_loaddata(objparam)
    },

    show_form(){
      this.showform = true
    },

    form_result(val){
      const objpage = this.get_page()
      //pr(objpage,`ipage: ${ipage}`)

      this.page.ipage = objpage.ipage
      const objparam = {
        page: {...objpage},
        filters: {}
      }      
      if(this.dbsearch)
        objparam.filters = get_filters(this.dbsearch, config)

      this.async_loaddata(objparam)
    },

    insert(){
      this.crudopt = "insert"
      this.show_form()
    },

    update(objrow){
      this.crudopt = "update"
      this.objrow = objrow
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
    
  }//methods  

};
</script>
