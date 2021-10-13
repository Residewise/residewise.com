<template>
  <div>
    <b-container>
      <b-row>
        <b-col sm="12" md="8" lg="8">
          <b-card class="mb-3">
            <div class="d-flex justify-content-between">
              <router-link :to="{ name: 'Dashboard' }">
                <span class="mdi mdi-arrow-left h4 m-0"></span>
              </router-link>
              <div>Property Hunt</div>
            </div>
            <hr>
             Set your search parameters and get notified of properties that match your criteria.

          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'index',
  computed: {
    ...mapGetters({
      user: 'auth/getUser'
    })
  },
  data () {
    return {}
  }
}
</script>

<style scoped>

</style>
