<template>
  <v-row align="center" justify="center">
    <v-col cols="7" class="elevation-8">
      <v-card elevation="0" class="title pa-1" color="secondary">
        <v-icon color="primary">mdi-lock</v-icon>
      </v-card>
      <v-form>
        <notificationerror v-if="objerror.message != ''" :title="objerror.title" :message="objerror.message" />
        <v-text-field
          ref="username"
          v-model="username"
          :error-messages="usernameErrors"
          :counter="10"
          label="Username"
          required
          @input="$v.username.$touch()"
          @blur="$v.username.$touch()"
        ></v-text-field>
        
        <v-text-field
          ref="password"
          v-model="password"
          :error-messages="passwordErrors"
          label="Password"
          type="password"
          required
          @input="$v.password.$touch()"
          @blur="$v.password.$touch()"
        ></v-text-field>

        <v-card-actions>
          <v-btn @click="clear">clear</v-btn>
          <v-spacer></v-spacer>
          <v-btn class="mr-4 primary--text" color="secondary" @click="submit">submit</v-btn>
        </v-card-actions>
      </v-form>

    </v-col>
  </v-row>
</template>


<script lang="ts">
import { validationMixin } from 'vuelidate'
import { required, maxLength, minLength } from 'vuelidate/lib/validators'
import api from "@/providers/api.ts"
import db from "@/helpers/localdb.ts"
import notificationerror from "@/components/common/notifications/notification_error.vue"

export default {
  mixins: [validationMixin],

  validations: {
    username: { required, minLength: minLength(5), maxLength: maxLength(10) },
    password: { required, minLength: minLength(8) },
  },  

  components:{
    notificationerror
  },

  data: () => ({
    valid: true,
    username: 'fulanito',
    password: 'menganito',
    objerror: {
      title: "",
      message: "",
    }
  }),//data

  computed: {
    usernameErrors () {
      const errors = []
      if (!this.$v.username.$dirty) return errors
      !this.$v.username.maxLength && errors.push('username must be at most 10 characters long')
      !this.$v.username.required && errors.push('username is required.')
      return errors
    },
    passwordErrors () {
      const errors = []
      if (!this.$v.password.$dirty) return errors
      //!this.$v.password.minLength && errors.push('Min length of pass is 8')
      !this.$v.password.required && errors.push('password is required')
      return errors
    },
  },

  methods: {
    
    submit : async function(){
      this.$v.$touch()
      console.log("on submit: ",this.username, this.password)
      const response = await api.get_async_apikey({username:this.username,password:this.password})
      if(response.error) {
        this.objerror.title = "Error"
        this.objerror.message = response.error.toString()
        this.$refs.username.focus()
        return
      }
      const apikey = response.data.data.result
      db.save("apikey",apikey)
    },

    clear () {
      this.$v.$reset()
      this.username = ''
      this.password = ''
      this.objerror = {title:"",message:""}
      this.$refs.username.focus()      
    },

  },
}
</script>
