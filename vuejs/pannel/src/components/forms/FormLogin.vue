<template>
  <v-row align="center">
    <v-col>
      <form>
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
        
        <v-btn class="mr-4" @click="submit">submit</v-btn>
        <v-btn @click="clear">clear</v-btn>    
      </form>
    </v-col>
  </v-row>
</template>


<script lang="ts">
import { validationMixin } from 'vuelidate'
import { required, maxLength, minLength } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],

  validations: {
    username: { required, maxLength: maxLength(10) },
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
      !this.$v.password.minLength && errors.push('Must be valid password')
      !this.$v.password.required && errors.push('password is required')
      return errors
    },
  },

  methods: {
    
    submit () {
      this.$v.$touch()
    },

    clear () {
      this.$v.$reset()
      this.username = ''
      this.password = ''
    },

  },
}
</script>
