<template>
  <div>
    <barover :isvisible="isfetching" />

    <v-data-table v-if="!isfetching" 
      :headers="headers" :search="search" :items="rows" 
      @click:row="on_rowclick"
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
        <v-btn class="m4-2" fab dark small color="cyan" @click="update(item)"><v-icon dark>mdi-pencil</v-icon></v-btn>
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
import barover from "@/components/common/bars/progress_barover.vue"
import detail from "@/modules/iprequest/detail.vue"
import forminsert from "@/modules/iprequest/form_insert.vue"
import formupdate from "@/modules/iprequest/form_update.vue"
import formdelete from "@/modules/iprequest/form_delete.vue"

export default {
  name: "iprequest-index",
  
  components: {
    //notifsnack,
    forminsert,
    detail,
    formupdate,
    formdelete,
    barover,
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
      { text: 'GET', value: 'hasget' },
      { text: 'POST', value: 'haspost' },
      { text: 'In BL', value: 'inbl' },
      { text: 'Day', value: 'insert_date' },
    ],
    rows: [],
    foundrows: 0,

  }),//data

  beforeMounted: async function(){
    alert("iprequest.index.beofremounted de list ^^ nunca pasa por aqui?")
    console.log("iprequest.index.beforeMount.islogged",this.islogged)
  },

  mounted: async function(){
    console.log("iprequest.index.mounted.async_islogged.response islogged:",this.islogged)
    this.isfetching = true
    //is_logged comprueba el token y setea this.islogged
    //si ha dado error no desloguea
    const response = await this.async_islogged()

    if(!this.islogged){
      this.$router.push({name:"login"})
      return
    }    
    
    //error network
    if(response.error){
      this.$emit("evterror",response.error)
      return 
    }

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
      console.log("list.methods.loaddata this.islogged: ",this.islogged)
      if(this.islogged){

        const response = await api.async_get_ip_request()
        this.rows = response.result
        this.foundrows = response.foundrows
        console.table("iprequest.index.async_loaddata.foundrows:",this.foundrows)
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

    on_rowclick(value){
      alert(JSON.stringify(value))
    },

    insert(){
      //alert(JSON.stringify("insert"))
      this.crudopt = "insert"
      //set showform=true
      this.show_dialog()
    },

    update(objrow){
      //alert(JSON.stringify(objrow))
      this.crudopt = "update"
      this.objrow = objrow
      //set showform=true
      this.show_dialog()
    },

    remove(objrow){
      this.crudopt = "delete"
      this.objrow = objrow
      this.show_dialog()
    },

  }//methods  

};
</script>
