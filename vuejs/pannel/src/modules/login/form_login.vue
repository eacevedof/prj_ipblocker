<template>
  <v-row align="center" justify="center">
    <v-col cols="7" class="elevation-8">
      <v-card elevation="0" class="title pa-1" color="secondary">
        <v-icon color="primary">mdi-lock</v-icon>
      </v-card>
      <v-form>
        <v-row v-if="error.message != ''">
          <v-col>
            <notificationerror :title="error.title" :message="error.message" />
          </v-col>
        </v-row>

        <v-text-field
          ref="username"
          v-model="username"
          :error-messages="get_username_errors"
          :counter="10"
          label="Username"
          required
          @input="$v.username.$touch()"
          @blur="$v.username.$touch()"
        ></v-text-field>
        
        <v-text-field
          ref="password"
          v-model="password"
          :error-messages="get_password_errors"
          label="Password"
          type="password"
          required
          @input="$v.password.$touch()"
          @blur="$v.password.$touch()"
        ></v-text-field>

        <v-card-actions>
          <v-btn @click="clear">clear</v-btn>
          <v-spacer></v-spacer>
          <v-btn class="mr-4 primary--text" color="secondary" @click="async_login">submit</v-btn>
        </v-card-actions>
      </v-form>

    </v-col>
  </v-row>
</template>

<script lang="ts">
import {mapMutations} from "vuex"
import { validationMixin } from 'vuelidate'
import { required, maxLength, minLength } from 'vuelidate/lib/validators'
import api from "../../providers/api"
import db from "../../helpers/localdb"
import notificationerror from "@/components/common/notifications/notification_error.vue"

export default {

  name: "formlogin",
  
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
    error: {
      title: "",
      message: "",
    }
  }),//data

  computed: {
    get_username_errors () {
      const errors = []
      if (!this.$v.username.$dirty) return errors
      !this.$v.username.maxLength && errors.push('username must be at most 10 characters long')
      !this.$v.username.required && errors.push('username is required.')
      return errors
    },
    get_password_errors () {
      const errors = []
      if (!this.$v.password.$dirty) return errors
      //!this.$v.password.minLength && errors.push('Min length of pass is 8')
      !this.$v.password.required && errors.push('password is required')
      return errors
    },
  },

  methods: {
    ...mapMutations(["set_islogged"]),

    async_login : async function(){
      this.$v.$touch()
      console.log("on async_submit: ",this.username, this.password)
      const usertoken = await api.async_get_usertoken({username:this.username,password:this.password})

      if(usertoken.error) {
        this.error.title = "Error"
        this.error.message = usertoken.error.toString()
        this.$refs.username.focus()
        return
      }

      db.save("usertoken",usertoken)
      this.set_islogged(true)
      this.$router.push({ name: "iprequest"})
    },

    clear () {
      this.$v.$reset()
      this.username = ''
      this.password = ''
      this.error = {title:"",message:""}
      this.$refs.username.focus()      
    },

  },
  
}
</script>
