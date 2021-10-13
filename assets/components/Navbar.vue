<template>
  <div>
    <div>
      <b-navbar class="mb-3" toggleable="lg" type="light">
        <b-navbar-brand :to="{ name: 'Landing' }">
          <img :src="require('../logo.png')" height="35" />
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
        <b-collapse id="nav-collapse" is-nav>
          <!-- Right aligned nav items -->
          <b-navbar-nav class="ml-auto">
            <b-navbar-nav>
              <b-nav-item v-if="!isAuthenticated" :to="{ name: 'Login' }">{{ $t("login") }}</b-nav-item>
              <b-nav-item v-if="!isAuthenticated" :to="{ name: 'Register' }">{{ $t("register") }}</b-nav-item>
              <b-nav-item v-if="isAuthenticated" :to="{ name: 'Dashboard' }">{{ user.fullName }}</b-nav-item>
              <b-nav-item v-if="isAuthenticated" @click="submit">Logout</b-nav-item>
            </b-navbar-nav>
            <locale-switcher />
          </b-navbar-nav>
        </b-collapse>
      </b-navbar>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import LocaleSwitcher from "./LocaleSwitcher.vue";

export default {
  components: { LocaleSwitcher },
  name: "Navbar",
  computed: {
    ...mapGetters({
      isAuthenticated: 'auth/isAuthenticated',
      user: 'auth/getUser'
    })
  },
  methods: {
    ...mapActions({
      logout: 'auth/logout'
    }),
    submit () {
      this.logout()
      this.$router.replace({
        name: 'home'
      })
    }
  }
};
</script>

<style></style>
