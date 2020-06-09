<template>
  <v-row align="center" justify="center">
    <v-col cols="7" class="elevation-8">
      <v-card elevation="0" class="title pa-1" color="secondary">
        <v-icon color="primary">mdi-lock</v-icon>
      </v-card>
      <v-form
      >
        <v-text-field
          v-model="username"
          :error-messages="usernameErrors"
          :counter="10"
          label="Username"
          required
          @input="$v.username.$touch()"
          @blur="$v.username.$touch()"
        ></v-text-field>
        
        <v-text-field
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

export default {
  mixins: [validationMixin],

  validations: {
    username: { required, minLength: minLength(5), maxLength: maxLength(10) },
    password: { required, minLength: minLength(8) },
  },  

  data: () => ({
    valid: true,
    username: '',
    password: '',
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
    
    submit () {
      this.$v.$touch()
      console.log("on submit: ",this.username, this.password)
    },

    clear () {
      this.$v.$reset()
      this.username = ''
      this.password = ''
    },

  },
}
</script>
