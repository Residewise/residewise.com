<template>
  <div>
    <b-row class="h90" align-v="center" align-h="center">
      <b-col sm="10" md="5" lg="4">
        <b-card :title="$t('register')" style="width:100%" class="shadow">
          <b-form @submit.prevent="submit">
            <b-form-group class="mb-3">
              <b-input-group>
                <b-form-input :placeholder="$t('first-name')"></b-form-input>
                <b-form-input :placeholder="$t('last-name')"></b-form-input>
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

            <b-button block pill size="lg" type="submit" variant="primary">{{
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
  name: "Register",
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
      },
    };
  },
  methods: {
    ...mapActions({
      register: "auth/register",
    }),
    submit() {
      this.register(this.form).then(() => {
        console.log("finished");
      });
    },
  },
};
</script>
