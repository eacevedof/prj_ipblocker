<template>
  <v-row align="center">
    <v-col>
      <v-form>
        <v-text-field prepend-icon="person" name="Username" label="Username" v-model="username"></v-text-field>
        <v-text-field prepend-icon="lock" name="Password" label="Password" type="password" v-model="password"></v-text-field>
        <v-card-actions>
          <v-btn primary large block>Login</v-btn>
        </v-card-actions>
      </v-form>
    </v-col>
  </v-row>
</template>


<script lang="ts">
import { validationMixin } from 'vuelidate'
import { required, maxLength, email } from 'vuelidate/lib/validators'

export default {
  data: () => ({
    valid: true,
    username: '',
    usernameRules: [
      v => !!v || 'Name is required',
      v => (v && v.length <= 10) || 'Name must be less than 10 characters',
    ],
    password: '',
    passRules: [
      v => !!v || 'E-mail is required',
      v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
    ],
  }),//data

  methods: {
    submit () {
      this.$refs.form.validate()
    },

  },

}
</script>
