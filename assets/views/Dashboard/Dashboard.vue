<template>
  <div>
    <b-container>
      <b-row>
        <b-col sm="12" md="8" lg="8">
          <b-card class="mb-3 border-0">
            <b-row>
              <b-col cols="12">
                <b-card class="mb-3 border-0" bg-variant="light">
                  <div class="d-flex">
                    <b-avatar :src="user.avatar" size="6rem" class="mr-3"></b-avatar>
                    <div>
                      <div>
                        <span v-if="user.identityVerifiedAt" class="mdi mdi-check-decagram text-primary"></span>
                        <b>{{ user.fullName }}</b></div>
                      <div>
                        <span class="mdi mdi-star text-warning"></span>
                        <span class="mdi mdi-star text-warning"></span>
                        <span class="mdi mdi-star text-warning"></span>
                        <span class="mdi mdi-star text-black-50"></span>
                        <span class="mdi mdi-star text-black-50"></span>
                      </div>
                      <div class="small">
                        <span class="mdi mdi-clock-outline"></span> {{ user.createdAt | moment('DD.MM.YY') }}
                      </div>
                    </div>

                  </div>
                </b-card>
              </b-col>

              <b-col cols="6" sm="6" md="6" lg="4">
                <router-link class="dashboard-option" :to="{ name: 'DashboardContracts' }">
                  <div class="dashboard-option-body">
                    <span class="mdi mdi-file-check-outline h3"></span>
                    <div> contracts</div>
                  </div>
                </router-link>
              </b-col>
              <b-col cols="6" sm="6" md="6" lg="4">
                <router-link class="dashboard-option" :to="{ name: 'DashboardMessages' }">
                  <div class="dashboard-option-body">
                    <span class="mdi mdi-forum h3"></span>
                    <div>messages</div>
                  </div>
                </router-link>
              </b-col>
              <b-col cols="6" sm="6" md="6" lg="4">
                <router-link class="dashboard-option" :to="{ name: 'DashboardProperties' }">
                  <div class="dashboard-option-body">
                    <span class="mdi mdi-home h3"></span>
                    <div> properties</div>
                  </div>
                </router-link>
              </b-col>
              <b-col cols="6" sm="6" md="6" lg="4">
                <router-link class="dashboard-option" :to="{ name: 'DashboardPropertyHunt' }">
                  <div class="dashboard-option-body">
                    <span class="mdi mdi-home-search h3"></span>
                    <div>property hunt</div>
                  </div>
                </router-link>
              </b-col>
              <b-col cols="6" sm="6" md="6" lg="4">
                <router-link class=" dashboard-option" :to="{ name: 'DashboardRatings' }">
                  <div class="dashboard-option-body">
                    <span class="mdi mdi-star-shooting h3"></span>
                    <div>rating</div>
                  </div>
                </router-link>
              </b-col>
              <b-col cols="6" sm="6" md="6" lg="4">
                <router-link class="dashboard-option" :to="{ name: 'DashboardAccount' }">
                  <div class="dashboard-option-body">
                    <span class="mdi mdi-bank h3"></span>
                    <div> Account</div>
                  </div>
                </router-link>
              </b-col>
            </b-row>
          </b-card>

        </b-col>
        <b-col offset-lg="1" sm="12" md="3" lg="3">
          <div class="shadow bg-white h-100 p-1 rounded text-right" style="background-image: url('https://images.pexels.com/photos/8530278/pexels-photo-8530278.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260');background-size: cover">
            <span class="mdi mdi-alpha-a-circle text-white"></span>

            <div class="text-white text-center mt-5 pt-5 h3">
              <div>Buy <b>Wine</b>. now!</div>
            <div class="font-weight-light">£1.50</div>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Dashboard',
  data () {
    return {
      imageEdit: false
    }
  },
  computed: {
    ...mapGetters({
      user: 'auth/getUser',
      token: 'auth/getToken'
    })
  }
}
</script>

<style>

</style>
