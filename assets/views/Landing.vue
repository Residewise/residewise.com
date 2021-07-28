<template>
  <div>
    <b-container>
      <b-row>
        <b-col xs="10" sm="10" md="12">
          <b-form @change.prevent="search">
            <b-card class="mb-3">
              <div class="card-body pb-0">
                <b-row align-h="between">
                  <b-form-group>
                    <b-form-radio-group
                      id="btn-radios-2"
                      v-model="form.type"
                      :options="types"
                      :aria-describedby="ariaDescribedby"
                      button-variant="outline-primary"
                      size="lg"
                      buttons
                    ></b-form-radio-group>
                  </b-form-group>

                  <b-form-group>
                    <b-form-radio-group
                      id="btn-radios-2"
                      v-model="form.contract"
                      :options="contracts"
                      :aria-describedby="ariaDescribedby"
                      button-variant="outline-primary"
                      size="lg"
                      buttons
                    ></b-form-radio-group>
                  </b-form-group>

                  <div>
                    <b-input-group>
                      <b-form-input
                        size="lg"
                        v-model="form.min"
                        :placeholder="$t('min-price')"
                      ></b-form-input>
                      <b-form-input
                        size="lg"
                        v-model="form.max"
                        :placeholder="$t('max-price')"
                      ></b-form-input>
                    </b-input-group>
                  </div>
                </b-row>
                <b-row class="advanced"> </b-row>
              </div>
            </b-card>
          </b-form>
        </b-col>
      </b-row>
      <b-row>
        <b-col
          xs="10"
          sm="10"
          md="4"
          v-for="property in properties"
          :key="property.id"
        >
          <div class="card mb-3">
            <div class="card-body">
              <div class="d-flex justify-content-between ">
                <div class="lead">{{ property.title }}</div>
                <div>
                  {{
                    Intl.NumberFormat("en-us", {
                      style: "currency",
                      currency: property.currency,
                    }).format(property.fee)
                  }}
                  <div class="small">
                    <span v-if="property.term == 'TERM_ONE_TIME'"
                      >Purchase</span
                    >
                    <span v-if="property.term == 'TERM_DAILY'">Daily</span>
                    <span v-if="property.term == 'TERM_WEEKLY'">Weekly</span>
                    <span v-if="property.term == 'TERM_MONTHLY'">Monthly</span>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <b-carousel
                :id="'carousel-' + property.id"
                interval
                fade
                controls
                img-width="350"
                img-height="250"
              >
                <b-carousel-slide
                  img-src="https://picsum.photos/1024/480/?image=10"
                ></b-carousel-slide>
                <b-carousel-slide
                  img-src="https://picsum.photos/1024/480/?image=12"
                ></b-carousel-slide>
                <b-carousel-slide
                  img-src="https://picsum.photos/1024/480/?image=22"
                ></b-carousel-slide>
              </b-carousel>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <div v-if="property.type == 'TYPE_HOUSE'">
                <b-icon font-scale="1.3" icon="house"></b-icon>
              </div>
              <div v-else-if="property.type == 'TYPE_APARTMENT'">
                <b-icon font-scale="1.3" icon="building"></b-icon>
              </div>
              <div v-else-if="property.type == 'TYPE_LAND'">
                <b-icon font-scale="1.3" icon="map"></b-icon>
              </div>
              <b-icon font-scale="1.3" icon="map"></b-icon>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";
import i18n from "../il8n";
export default {
  name: "Landing",
  data() {
    return {
      form: {
        type: "TYPE_APARTMENT",
        contract: "CONTRACT_RENT",
        min: null,
        max: null,
        city: "Praha",
      },
      types: [
        { text: i18n.t("any"), value: null },
        { text: i18n.t("flat"), value: "TYPE_APARTMENT" },
        { text: i18n.t("house"), value: "TYPE_HOUSE" },
        { text: i18n.t("land"), value: "TYPE_LAND" },
      ],
      contracts: [
        { text: i18n.t("any"), value: null },
        { text: i18n.t("rent"), value: "CONTRACT_RENT" },
        { text: i18n.t("buy"), value: "CONTRACT_SELL" },
      ],

      cities: [
        "Praha",
        "Brno",
        "Ostrava",
        "Plzeň",
        "Liberec",
        "Olomouc",
        "Ústí nad Labem",
        "Hradec Králové",
        "České Budějovice",
        "Pardubice",
        "Havířov",
        "Zlín",
        "Kladno",
        "Most",
        "Karviná",
        "Frýdek-Místek",
        "Opava",
        "Karlovy Vary",
        "Teplice",
        "Děčín",
        "Jihlava",
        "Chomutov",
        "Přerov",
        "Mladá Boleslav",
      ],
    };
  },
  computed: {
    ...mapGetters({
      properties: "property/getProperties",
    }),
  },
  methods: {
    ...mapMutations({
      setLoading: "setLoading",
    }),
    search() {
      this.setLoading(true);
      this.$store.dispatch("property/get", this.form);
      this.setLoading(false);
    },
  },
};
</script>

<style scoped>
.vh-80 {
  min-height: 75vh;
}
.custom-radio {
  border-radius: 1rem;
  display: flex;
  background-color: rgba(0, 0, 0, 0.05);
}
.custom-radio div {
  padding: 1rem;
  border-radius: 1rem;
  min-width: 70px;
  text-align: center;
  text-transform: capitalize;
}

.custom-radio div:hover {
  cursor: pointer;
}

.custom-radio .active {
  background-color: #167958;
  color: #ffffff;
}

.btn-group {
  background-color: rgba(0, 0, 0, 0.05);
  border-radius: 0.3rem;
}
</style>
