<template>
  <div>
    <v-progress-circular v-if="isfetching" indeterminate size="64"></v-progress-circular>
    <v-data-table :headers="headers" :items="rows" class="elevation-3">
      <!-- inyecta en la cabecera de la tabla en la zona top-->
      <template v-slot:top>
        
        <v-system-bar color="yellow lighten-1" />

        <v-toolbar color="black darken-4">
          
          <v-btn
            class="mx-2"
            :elevation="10"
            fab dark color="light-green accent-4"
            @click="show_dialog" 
          ><v-icon>mdi-plus</v-icon></v-btn>

          <v-divider class="mx-4" inset vertical/>
          <v-toolbar-title class="yellow--text font-weight-bold">IP Request</v-toolbar-title>
          <v-spacer></v-spacer>

          <!-- los hijos se comunican por eventos con los padres -->
          <formedit v-if="crudopt=='edit'" :objrow="objrow" :isvisible="showform" v-on:evtedit="dialog_result" v-on:evtclose="showform=false" />

          <formremove v-if="crudopt=='remove'" :objrow="objrow" :isvisible="showform" v-on:evtremove="dialog_result" v-on:evtclose="showform=false" />

        </v-toolbar>

        <!-- barra busqueda -->
        <v-col>
          <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details />
        </v-col>

      </template>
      <!-- fin cabecer tabla -->

      <!-- botones columna -->
      <template v-slot:item.colbuttons="{ item }">
        <v-btn class="m4-2" fab dark small color="cyan" @click="edit(item)"><v-icon dark>mdi-pencil</v-icon></v-btn>
        <v-btn class="m4-2" fab dark small color="error" @click="remove(item)"><v-icon dark>mdi-delete</v-icon></v-btn>
      </template>

      <!--
      <notifsnack :showsnack="showsnack" :innertext="textsnack" v-on:evtclose="showsnack=false" />
      -->
    </v-data-table>
  </div>
</template>

<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import api from "../../providers/api"

import notifsnack from "@/components/common/notifications/notification_snackbar.vue"
import formedit from "@/modules/iprequest/form_edit.vue"
import formremove from "@/modules/iprequest/form_remove.vue"


export default {
  name: "list",
  
  components: {
    //notifsnack,
    formedit,
    formremove,
  },

  data: () => ({
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
      { text: 'GET', value: 'get' },
      { text: 'POST', value: 'post' },
      { text: 'In BL', value: 'inbl' },
      { text: 'Day', value: 'insert_date' },
    ],
    rows: [],
    foundrows: 0,

  }),//data

  mounted: async function(){
    //alert("list mounted")
    console.log("iprequest.list.mounted async")
    await this.async_islogged()
    await this.async_loaddata()
  },

  computed:{
    ...mapState(["islogged"]),
  },

  methods:{
    //setters
    ...mapActions(["async_islogged"]),

    async_loaddata: async function(){
      console.log("list.methods.loaddata this.islogged: ",this.islogged)
      if(this.islogged){
        this.isfetching = true
        const response = await api.async_get_ip_request()
        this.rows = response.result
        this.foundrows = response.foundrows
        this.isfetching = false
        //console.table(this.rows)
      }
    },

    show_dialog(){
      this.showform = true
    },

    dialog_result(val){
      //alert("dialog_result: "+val)
      this.async_loaddata()
    },

    detail(){;},

    edit(objrow){
      //alert(JSON.stringify(objrow))
      this.crudopt = "edit"
      this.objrow = objrow
      this.show_dialog()
    },

    remove(objrow){
      this.crudopt = "remove"
      this.objrow = objrow
      this.show_dialog()
    },

  }//methods  

};
</script>
