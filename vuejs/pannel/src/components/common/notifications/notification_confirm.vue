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
          color="yellow darken-1"
          text
          @click="action('cancel')"
        >
          Disagree
        </v-btn>

        <v-btn
          color="black darken-1"
          text
          @click="action('ok')"
        >
          Agree
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
      this.$emit("evtaction",type)
    }
  }
}
</script>
