<template>
  <form @submit.prevent="fn_addtodo">
    <div class="form-group" >
      <label for="todo">Email address</label>
      <input type="text"  v-model="todo" autocomplete="off" class="form-control" id="todo" autofocus >
    </div>
    <button type="submit" class="btn btn-primary btn-block">Agregar todo</button>
  </form>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";

@Component
export default class TodoForm extends Vue{

  todo: string = "";

  fn_addtodo(){
    this.$emit("fn_newtodo",this.todo)
    this.todo = "";
    console.log("this.todo:",this.todo)
  }
  
}
</script>