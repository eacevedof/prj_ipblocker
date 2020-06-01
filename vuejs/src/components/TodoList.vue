<template>
  <div>
    <TodoForm @fn_newtodo="fn_newtodo($event)" />
    <hr />

    <button @click="fn_removetodos" class="btn btn-dark btn-block">Eliminar todos</button>
    <br/>

    <table class="table">
      <thead>
        <th>id</th>
        <th>todo</th>
        <th>eliminar</th>
      </thead>

      <tbody v-if="todos.length > 0">
        <tr v-for="(todo, index) in todos" :key="index">
          <td>{{ index + 1 }}</td>
          <td>{{ todo }}</td>
          <td>
            <button @click="fn_removetodo(index)" class="btn btn-danger btn-block">
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>

      <tbody v-else>
        <tr>
          <td colspan="3">No todos</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import TodoForm from "@/components/TodoForm.vue";

@Component({
  components:{
    TodoForm
  }
})

export default class TodoList extends Vue{
  
  todos: Array<string> = [];
  
  fn_newtodo(todo:string){
    console.log("fn_newtodo.todo",todo)
    this.todos.push(todo)
  }

  fn_removetodo(index:any){
    this.todos.splice(index,1)
  }

  fn_removetodos(){
    this.todos = []
  }

}

</script>