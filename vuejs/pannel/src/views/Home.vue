<template>
  <v-container>
    <v-layout wrap>
      <v-flex class="primary" xs12>
        <h1>12</h1>
      </v-flex>
      <v-flex class="secondary" xs6>
        <h1>6</h1>
      </v-flex>  
      <v-flex class="red" xs6>
        <h1>6</h1>
      </v-flex>            
    </v-layout>

    <v-layout class="mt-5 secondary" justify-center align-center style="height:300px">
      <v-flex class="info" xs6>
        <h1>Centrado</h1>
      </v-flex>
      <v-flex class="pink lighten-1 white--text" xs4>
        <h1>Centrado</h1>
      </v-flex>      
    </v-layout>

    <v-layout>
      <v-flex>
        <h1 class="display-1 text-uppercase">Lorem, ipsum dolor.</h1>
        <h1 class="display-2 primary pa-5">Lorem ipsum dolor sit.</h1>
        <h1 class="display-3 text-xs-center">Lorem ipsum dolor sit.</h1>
        <h1 class="display-4">Lorem ipsum dolor sit amet.</h1>
        
      </v-flex>    
    </v-layout>

  </v-container>
</template>

<script>
export default {
  name: "Home"
};
</script>
