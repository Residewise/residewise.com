<template>
  <div>
    <b-container>
      <b-row>
        <b-col sm="12" md="8" lg="8">
          <b-card class="mb-3 border-0">
            <div class="d-flex justify-content-between pb-1 mb-3">
              <router-link :to="{ name: 'Dashboard' }">
                <span class="mdi mdi-arrow-left h4 m-0"></span>
              </router-link>
              <div class="font-weight-light h5 m-0">Properties</div>
            </div>
            <b-card-group columns>
              <router-link class="card text-center border dashboard-option-secondary" :to="{ name: 'DashboardPropertyCreate' }">
                <div class="card-body">
                  <span class="mdi mdi-home-plus h3"></span>
                  <div>Add property</div>
                </div>
              </router-link>

              <b-card class="border" img-src="https://images.pexels.com/photos/4553180/pexels-photo-4553180.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260">
                <div>
                  <div>A property</div>
                </div>
              </b-card>

              <b-card class="border" img-src="https://images.pexels.com/photos/4553180/pexels-photo-4553180.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260">
                <div>
                  <div>A property</div>
                </div>
              </b-card>

              <b-card class="border" img-src="https://images.pexels.com/photos/4553180/pexels-photo-4553180.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260">
                <div>
                  <div>A property</div>
                </div>
              </b-card>

              <b-card class="border" img-src="https://images.pexels.com/photos/4553180/pexels-photo-4553180.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260">
                <div>
                  <div>A property</div>
                </div>
              </b-card>

              <b-card class="border" img-src="https://images.pexels.com/photos/4553180/pexels-photo-4553180.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260">
                <div>
                  <div>A property</div>
                </div>
              </b-card>

              <b-card class="border" img-src="https://images.pexels.com/photos/4553180/pexels-photo-4553180.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260">
                <div>
                  <div>A property</div>
                </div>
              </b-card>

              <b-card class="border" img-src="https://images.pexels.com/photos/4553180/pexels-photo-4553180.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260">
                <div>
                  <div>A property</div>
                </div>
              </b-card>

            </b-card-group>

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
