<template>
  <div id="module-iprequest-list">
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
  
    <template>
      <div class="text-center ma-2">
        <v-snackbar v-model="snackbar">
          {{txtsnack}}
          <v-btn color="info" text @click="snackbar=false">Cerrar</v-btn>
        </v-snackbar>
      </div>
    </template>    
  </div>
</template>

<script lang="ts">
export default {
  name: 'list',
  

  props: {

  },
}
</script>

<style>
</style>
