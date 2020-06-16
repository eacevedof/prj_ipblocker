<template>
  <div>
    <barover :isvisible="isfetching" />

    <submenu :isvisible="issubmenu" :evtclick="evtsubmenu" v-on:evtselected="submenu_selected" v-on:evtclose="issubmenu=false" />

    <v-data-table v-if="!isfetching" 
      :headers="headers" 
      :items="arrows"
      :page.sync="page.ipage"
      :items-per-page="page.ippage"
      hide-default-footer
      @page-count="page.ipages - $event"
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
          <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details />
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
    <div class="text-center pt-2">
      <v-pagination v-model="page.ipage" :length="page.ipages"></v-pagination>
      <v-text-field
        :value="page.ippage"
        label="Items per page"
        type="number"
        min="-1"
        max="15"
        @input="page.ippage = parseInt($event, 10)"
      ></v-text-field>
    </div>    
  </div>
</template>

<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import api from "../../providers/api"
import url from "../../helpers/url"

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
      ipage: 1,
      ippage: 50,
      ipages: 0,
      foundrows:0,
    },

  }),//data

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
    this.isfetching = true
    await this.async_loaddata()
    this.isfetching = false
  },

  computed:{
    ...mapState(["islogged"]),
  },

  methods:{
    //setters
    ...mapActions(["async_islogged"]),

    async_loaddata: async function(){

      const ippage = this.page.ippage
      const ipage = this.get_page()
      alert(ipage)
      const ifrom = (ipage-1) * ippage
      const objpage = {ippage,ifrom}
      //console.log("async_loaddata.router.params",url.get_param("page"))
      //console.log("async_loaddata.router.query",url.get_get("ip"))
      const response = await api.async_get_ip_request(objpage)
      this.arrows = response.result
      this.page.foundrows = response.foundrows
      //this.page.ipages = Math.ceil(this.arrows.length/ippage)
      this.page.ipages = Math.ceil(this.page.foundrows/ippage)
      
      console.table("iprequest.index.async_loaddata.page.count:",this.page.foundrows)
      
    },

    show_form(){
      this.showform = true
    },

    dialog_result(val){
      //alert("dialog_result: "+val)
      this.async_loaddata()
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
      return ipage
    },


  }//methods  

};
</script>
