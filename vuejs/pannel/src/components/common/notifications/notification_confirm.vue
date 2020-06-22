<template>
<v-row justify="center">
  <v-dialog
    v-model="is_visible"
    max-width="290"
  >
    <v-card>
      <v-card-title class="headline" v-if="title!=''">{{title}}</v-card-title>

      <v-card-text v-if="message!=''">
        {{ message }}
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          @click="action('cancel')"
        >
          Cancel
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          class="mr-4 primary--text" color="secondary" 
          @click="action('accept')"
        >
          Accept
        </v-btn>
      </v-card-actions>
    </v-card>

  </v-dialog>
</v-row>
</template>

<script lang="ts">
export default {
  name: 'notificationconfirm',
  props: {
    isvisible: Boolean,
    title: String,
    message: String
  },
 computed:{
    is_visible:{
      get(){
        return this.isvisible
      },
      set(v){
        this.$emit("evtclose",v)
      }
    }
  },
  methods:{
    action(type){
      this.$emit("evtoption",type)
    }
  }
}
</script>
