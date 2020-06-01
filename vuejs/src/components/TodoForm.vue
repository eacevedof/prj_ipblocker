<template>
  <form>
    <div class="form-group">
      <label for="input-todo">Email address</label>
      <input type="text" class="form-control" id="input-todo" aria-describedby="emailHelp" placeholder="Enter email">
      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";


export default class TodoForm extends Vue{
  
}
</script>