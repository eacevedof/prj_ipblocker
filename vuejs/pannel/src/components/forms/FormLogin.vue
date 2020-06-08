<template>
  <v-row align="center">
    <v-col>
      <v-form>
    
      </v-form>
    </v-col>
  </v-row>
</template>


<script lang="ts">
import { validationMixin } from 'vuelidate'
import { required, maxLength, password } from 'vuelidate/lib/validators'

export default {
  mixins: [validationMixin],

  validations: {
    username: { required, maxLength: maxLength(10) },
    password: { required, password },
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
      !this.$v.password.password && errors.push('Must be valid password')
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
