<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />

    <v-data-table :headers="headers" :items="rows" class="elevation-3">
      <template v-slot:top>
        
        <v-system-bar color="yellow lighten-1" />

        <v-toolbar color="black darken-4">
          
          <v-btn
            class="mx-2"
            :elevation="10"
            fab dark color="light-green accent-4"
            @click="accionx"
          ><v-icon>mdi-plus</v-icon></v-btn>

          <v-divider class="mx-4" inset vertical/>
          <v-toolbar-title class="yellow--text font-weight-bold">IP Request</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            
            <template v-slot:activator="{}"></template>

            <v-card>
              <v-card-title class="cyan white-text">
                <span class="headline">{{dialogtitle}}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editado.remote_ip" label="marca"></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer />
                <v-btn color="blue-grey" class="ma-2 white--text" @click="cancelar" >Cancelar</v-btn>
                <v-btn color="teal accent-4" class="ma-2 white--text" @click="guardar" >Guardar</v-btn>
              </v-card-actions>

            </v-card>
          </v-dialog>
          
        </v-toolbar>

        <!-- barra busqueda -->
        <v-col>
          <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details />
        </v-col>
      </template>

      <!-- botones columna -->
      <template v-slot:item.accion="{ item }">
        <v-btn class="m4-2" fab dark small color="cyan" @click="editar(item)"><v-icon dark>mdi-pencil</v-icon></v-btn>
        <v-btn class="m4-2" fab dark small color="error" @click="borrar(item)"><v-icon dark>mdi-delete</v-icon></v-btn>
      </template>

    </v-data-table>
  
    <notifsnack showsnack="showsnack" innertext="txtsnack" />

  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import notifsnack from "@/components/common/notifications/notification_snackbar.vue"
import Scrumbs from "@/components/navigation/Scrumbs.vue"
import api from "../providers/api"
import db from "../helpers/localdb"

export default {
  name: "IpRequest",
  
  components: {
    Scrumbs,
    notifsnack,
  },

  data: () => ({
    showsnack: true,
    txtsnack: "Texto snack",
    dialog: false,
    dialogtitle: "",
    search: "",


    headers: [
      {
        text: 'nº',
        align: 'start',
        sortable: true,
        value: 'id',
      },
      { text: 'Rem. IP', value: 'remote_ip' },
      { text: 'Domain', value: 'domain' },
      { text: 'R. URI', value: 'request_uri' },
      { text: 'GET', value: 'get' },
      { text: 'POST', value: 'post' },
      { text: 'In BL', value: 'inbl' },
      { text: 'Day', value: 'insert_date' },
      { text: 'Action', value: 'accion' },

    ],
    rows: [],
    editedIndex: -1,
    foundrows: 0,
    editado: {
      marca:""
    }
  }),//data

  beforeMount: function(){
    ;
    //comprobar si se esta logado, si no está redirect a login
  },

  mounted: function(){
    this.load_data()
  },

  created(){;},

  computed:{
    ...mapState(["islogged"]),
  },

  watch:{},

  methods:{
    //setters
    ...mapActions(["async_islogged"]),

    load_data: async function(){
      await this.async_islogged()
      if(!this.islogged)
        this.$router.push({name:"login"})

      console.log("iprequest.mounted.islogged: ",this.islogged)
      if(this.islogged){
        const usertoken = db.select("usertoken")
        if(!usertoken) return
        const response = await api.async_get_ip_request(usertoken)
        this.rows = response.result
        this.foundrows = response.foundrows
        //alert(this.foundrows)
        //alert(JSON.stringify(response));
        //console.log("THIS.ROWS: ",this.foundrows)
        console.table(this.rows)
      }
    },

    accionx(){
      //alert("accionx")
      this.dialog = true
    },

    cancelar(){;},

    guardar(){;},

    borrar(){;},

    editar(){;},

  }  

};
</script>