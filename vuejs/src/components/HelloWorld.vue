<template>
  <div class="hello">
    <h1>{{ msg }}</h1>
    <p>
      For a guide and recipes on how to configure / customize this project,<br>
      check out the
      <a href="https://cli.vuejs.org" target="_blank" rel="noopener">vue-cli documentation</a>.
    </p>
    <h3>Installed CLI Plugins</h3>
    <p @click="on_click">{{ get_fullname }}</p>
  
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class HelloWorld extends Vue {
  @Prop() private msg!: string;

  name: string = "Eduardo";
  surname: string = "A. F.";

  get get_fullname() {
    //return `fullname: ${this.name} ${this.surname}`
    return this.$store.state.fullname
  }

  on_click() {
    alert(1)
  }

  mounted(){
    console.log("componente montado")
  }

  destroyed(){
    console.log("componente destroyed")
  }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
