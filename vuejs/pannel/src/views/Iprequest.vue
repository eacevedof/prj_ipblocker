<!--login.vue-->
<template>
  <v-container>

    <scrumbs pagename="iprequest" />

    <div class="mt-5">
      {{ islogged }}
    </div>

    <v-data-table :headers="headers" :items="rows" class="elevation-3">
      <template v-slot:top>
        
        <v-system-bar color="indigo darken-2" />

        <v-toolbar color="indigo">
          <v-btn
            class="mx-2"
            :elevation="10"
            fab dark color="teal accent-4"
            @click="accionx"
          ><v-icon>mdi-plus</v-icon></v-btn>
          <v-divider class="mx-4" inset vertical/>
          <v-toolbar-title class="">IP Request</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator>

            </template>
            <v-card>
              <v-card-title class="cyan white-text">
                <span class="headline">{{dialogtitle}}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field v-model="editado.marca" label="marca"></v-text-field>
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
        <v-btn class="m4-2" fab dark small color="error" @click="borrar(item)"><v-icon dark>mdi-pencil</v-icon></v-btn>
      </template>

    </v-data-table>
  
    <template>
      <div class="text-center ma-2">
        <v-snackbar v-model="snackbar">
          {{txtsnack}}
          <v-btn color="info" text @click="snackbar=false">Cerrar</v-btn>
        </v-snackbar>
      </div>
    </template>

  </v-container>
</template>
<script lang="ts">
import {mapMutations, mapActions, mapState} from "vuex"
import Scrumbs from "@/components/navigation/Scrumbs.vue"
import api from "@/providers/api.ts"
import db from "@/helpers/localdb.ts"

export default {
  name: "IpRequest",
  
  components: {
    Scrumbs,
  },

  data: () => ({
    snackbar: true,
    txtsnack: "",
    ison:false,
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
      { text: 'Day', value: 'insert_date' },

    ],
    rows: [],
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

  computed:{
    ...mapState(["islogged"])
  },

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