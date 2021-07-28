<template>
  <div>
    <b-row class="h90" align-v="center" align-h="center">
      <b-col sm="10" md="5" lg="4">
        <b-card :title="$t('login')" style="width:100%" class="shadow">
          <b-form @submit.prevent="submit">
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

            <b-button pill size="lg" block type="submit" variant="primary">{{
              $t("contiune")
            }}</b-button>
          </b-form>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<style scoped></style>

<script>
import { mapActions } from "vuex";
export default {
  name: "Login",
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
    };
  },
  methods: {
    async submit() {
      this.$auth.login({
        body: this.form,
        redirect: { name: "Dashboard" },
        fetchUser: true,
      });
    },
  },
};
</script>
