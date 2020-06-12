<template>
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
        <formedit :objrow="objrow" :ison="showdialog" v-on:evtresult="dialog_result" v-on:evtclose="showdialog=$event.value" />

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

    <notifsnack :showsnack="showsnack" :innertext="textsnack" />
  </v-data-table>
</template>

<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import api from "../../providers/api"
import db from "../../helpers/localdb"

import notifsnack from "@/components/common/notifications/notification_snackbar.vue"
import formedit from "@/modules/iprequest/form_edit.vue"


export default {
  name: "list",
  
  components: {
    notifsnack,
    formedit,
  },

  data: () => ({
    showsnack: true,
    textsnack: "",

    showdialog: false,

    search: "",
    
    objrow: {
      id: "",
      remote_ip: "",
      domain: ""
    },

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
    editingindex: -1,

  }),//data

  mounted: async function(){
    //alert("list mounted")
    console.log("iprequest.list.mounted")
    await this.load_data()
  },

  computed:{
    ...mapState(["islogged"]),
  },

  methods:{
    //setters
    ...mapActions(["async_islogged"]),

    load_data: async function(){
      console.log("iprequest.mounted.islogged: ",this.islogged)
      if(this.islogged){
        const usertoken = db.select("usertoken")
        const response = await api.async_get_ip_request(usertoken)
        this.rows = response.result
        this.foundrows = response.foundrows
        console.table(this.rows)
      }
    },

    show_dialog(){
      this.showdialog = true
    },

    dialog_result(value){
      this.showsnack = true
      this.textsnack = value
      this.load_data()
    },

    cancelar(){;},

    guardar(){;},

    remove(){;},

    detail(){;},

    edit(objrow){
      //alert(JSON.stringify(objrow))
      this.editingindex = this.rows.indexOf(objrow)
      //alert(this.editingindex)
      this.objrow = Object.assign({}, objrow)
      this.show_dialog()
    },

  }//methods  

};
</script>
