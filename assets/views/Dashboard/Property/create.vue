<template>
  <div>
    <b-container>
      <b-row>
        <b-col sm="10" md="10" lg="10">
          <b-card class="mb-3 border-0">
            <div>
              <h5 class="font-weight-light">Add Property</h5>
            </div>
            <b-row>
              <b-col cols="4">
                images,
                name,
                address
              </b-col>
              <b-col cols="8">
                features
              </b-col>
            </b-row>
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
