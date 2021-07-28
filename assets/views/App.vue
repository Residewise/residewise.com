<template>
  <div>
    <div v-if="isLoading">
      <div class="loading d-flex justify-content-center align-items-center ">
        <b-spinner label="Loading..."></b-spinner>
      </div>
    </div>
    <navbar />
    <router-view />
  </div>
</template>

<style scoped>
.loading {
  height: 100%;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 99999;
  background-color: rgba(255, 255, 255, 0.8);
}
</style>

<script>
import { mapGetters } from "vuex";
import Navbar from "../components/Navbar.vue";

export default {
  components: { Navbar },
  name: "App",
  computed: {
    ...mapGetters({
      isLoading: "getLoading",
    }),
  },
};
</script>
