<template>
  <div>
    <b-container>
      <b-row align-h="center">
        <b-col sm="12" md="10" lg="10">
          <b-card class="mb-3">
            <div class="d-flex justify-content-between mb-1">
              <router-link :to="{ name: 'Dashboard' }">
                <span class="mdi mdi-arrow-left h4 m-0"></span>
              </router-link>
              <div class="font-weight-light h5 m-0">Inbox</div>
            </div>

            <b-row>
              <b-col class="border-right" cols="12" md="4" lg="4">
                <b-form-input placeholder="search" class="mb-1"></b-form-input>
                <div class="message-list">
                  <b-list-group flush>

                    <b-list-group-item class="d-flex align-items-center justify-content-between px-1">
                      <div class="d-flex align-items-center">
                        <b-avatar class="mr-3"></b-avatar>
                        <div>J. Circlehead</div>
                      </div>
                      <b-avatar class="ml-3 close close text-white bg-danger" icon="x"></b-avatar>
                    </b-list-group-item>

                    <b-list-group-item class="d-flex align-items-center justify-content-between px-1">
                      <div class="d-flex align-items-center">
                        <b-avatar class="mr-3"></b-avatar>
                        <div>M. Portish</div>
                      </div>
                      <b-avatar class="ml-3 close text-white bg-danger" icon="x"></b-avatar>
                    </b-list-group-item>


                  </b-list-group>
                </div>
              </b-col>
              <b-col cols="12" md="8" lg="8">

                <div class="d-flex justify-content-end border-bottom pb-1 rounded">
                  <div>
                    <b-avatar class="mr-2" icon="file-earmark-plus-fill"></b-avatar>
                  </div>
                  <div>
                    <b-avatar icon="exclamation-diamond-fill" class="text-danger"></b-avatar>
                  </div>
                </div>

                <b-list-group class="conversation" flush>
                  <b-list-group-item class="border-0">
                    <div class="d-flex justify-content-between">
                      <div>
                        <b-avatar class="mr-3"></b-avatar>
                      </div>
                      <div class="bg-secondary rounded mb-1 p-2 shadow-sm">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of
                        type and scrambled it to make a type specimen book. It has survived not only five centuries, but
                        also the leap into electronic typesetting, remaining essentially unchanged. It was popularised
                        in
                        the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently
                        with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                      </div>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="border-0">
                    <div class="d-flex justify-content-between">
                      <div class="message-active rounded mb-1 p-2 shadow-sm">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of
                        type and scrambled it to make a type specimen book. It has survived not only five centuries, but
                        also the leap into electronic typesetting, remaining essentially unchanged. It was popularised
                        in
                        the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently
                        with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                      </div>
                      <div>
                        <b-avatar class="ml-3"></b-avatar>
                      </div>
                    </div>
                  </b-list-group-item>

                  <b-list-group-item class="border-0">
                    <div class="d-flex justify-content-between">
                      <div class="message-active rounded mb-1 p-2 shadow-sm">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of
                        type and scrambled it to make a type specimen book. It has survived not only five centuries.
                      </div>
                      <div>
                        <b-avatar class="ml-3"></b-avatar>
                      </div>
                    </div>
                  </b-list-group-item>

                </b-list-group>
                <hr class="mt-0 mb-2"/>
                <b-form-textarea
                    class="mt-0"
                    id="textarea"
                    v-model="text"
                    placeholder="Enter your message here..."
                    rows="3"
                ></b-form-textarea>
                <div class="d-flex justify-content-between mt-2">
                  <div>
                    <b-avatar icon="paperclip"></b-avatar>
                  </div>
                  <div>
                    <b-avatar variant="info" icon="cursor-fill"></b-avatar>
                  </div>
                </div>
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
.conversation {
  height: 50vh !important;
  overflow: hidden;
  overflow-y: scroll;

}

.message-list {
  height: 70vh !important;
  overflow: hidden;
  overflow-y: scroll;
}

.message-active {
  background-color: #90caf9;
}

.icon-btn {
  padding: 5px 7px;
  border-radius: 50%;
  background-color: rgba(0, 0, 0, 0.05);
  color: black;
  font-size: 24px;
}
</style>
