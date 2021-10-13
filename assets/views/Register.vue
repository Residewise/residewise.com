<template>
  <div>
    <b-row class="h90" align-v="center" align-h="center">
      <b-col sm="10" md="5" lg="4">
        <b-overlay :show="isLoading">
          <b-card :title="$t('register')" style="width:100%" class="shadow">
            <b-form @submit.prevent="submit">
              <b-form-group class="mb-3">
                <b-input-group>
                  <b-form-input v-model="form.firstName" :placeholder="$t('first-name')"></b-form-input>
                  <b-form-input v-model="form.lastName" :placeholder="$t('last-name')"></b-form-input>
                </b-input-group>
              </b-form-group>

              <b-form-group id="email-input-group" label-for="email-input">
                <b-form-input
                    id="email-input"
                    v-model="form.email"
                    type="email"
                    :placeholder="$t('email')"
                    required
                ></b-form-input>
              </b-form-group>
              <b-form-group id="password-input-groupd" label-for="password-input">
                <b-form-input
                    id="password-input"
                    v-model="form.password"
                    type="password"
                    :placeholder="$t('password')"
                    required
                ></b-form-input>
              </b-form-group>

              <div class="text-right">
                <b-button pill type="submit" variant="primary">{{
                    $t('contiune')
                  }}
                </b-button>
              </div>
            </b-form>
          </b-card>
        </b-overlay>
      </b-col>
    </b-row>
  </div>
</template>

<style scoped></style>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'Register',
  data () {
    return {
      isLoading: false,
      form: {
        firstName: '',
        lastName: '',
        email: '',
        password: ''
      }
    }
  },
  methods: {
    ...mapActions({
      register: 'auth/register'
    }),
    submit () {
      this.isLoading = true
      this.register(this.form).then(() => {
        this.$router.replace({
          name: 'Login'
        })
      }).finally(() => {
        this.isLoading = false
      })
    }
  }
}
</script>
