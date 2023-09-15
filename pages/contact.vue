<template>
  <div class="col-lg-8">
    <h3>{{ $t("contact.contactInformation") }}</h3>
    <p>{{ $t("contact.contactInformationDesc") }}</p>
    <div v-for="(address, index) in address_informations" :key="index">
      <div id="map" v-html="address.map"></div>
      <div class="pl-box p-3 my-3">
        <h4 class="text-center text-blue fw-bold d-flex flex-column">
          <i class="fa fa-map-marker-alt mb-3"></i>
          {{ $t("contact.address") }}
        </h4>
        <div class="pl-content text-center">
          {{ address.address }}
        </div>
      </div>
      <div class="pl-box p-3">
        <h4 class="text-center text-blue fw-bold d-flex flex-column">
          <i class="fa fa-envelope-open mb-3"></i>
          {{ $t("contact.email") }}
        </h4>
        <div class="pl-content text-center">
          {{ settings.email }}
        </div>
      </div>
      <div
        class="row mt-3"
        v-for="(item, index2) in address.phones"
        :key="index2"
      >
        <div class="col" v-if="item.phone">
          <div class="pl-box p-3">
            <h4 class="text-center text-blue fw-bold d-flex flex-column">
              <i class="fa fa-phone-volume mb-3"></i>
              {{ $t("contact.phone") }}
            </h4>
            <div class="pl-content text-center">
              <a
                rel="dofollow"
                :href="'tel:' + item.phone"
                :title="$t('contact.phone')"
                >{{ item.phone }}</a
              >
            </div>
          </div>
        </div>
        <div class="col" v-if="item.fax">
          <div class="pl-box p-3">
            <h4 class="text-center text-blue fw-bold d-flex flex-column">
              <i class="fa fa-fax mb-3"></i>
              {{ $t("contact.fax") }}
            </h4>
            <div class="pl-content text-center">
              <a
                rel="dofollow"
                :href="'tel:' + item.fax"
                :title="$t('contact.fax')"
                >{{ item.fax }}</a
              >
            </div>
          </div>
        </div>
        <div class="col" v-if="item.whatsapp">
          <div class="pl-box p-3">
            <h4 class="text-center text-blue fw-bold d-flex flex-column">
              <i class="fa fa-whatsapp mb-3"></i>
              {{ $t("contact.whatsapp") }}
            </h4>
            <div class="pl-content text-center">
              <a
                rel="nofollow"
                :href="
                  'https://wa.me/' +
                  item.whatsapp.replaceAll(' ', '') +
                  '?text=' +
                  settings.company_name
                "
                target="_blank"
                :title="$t('contact.whatsapp')"
                >{{ item.whatsapp }}</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  data() {
    return {
      settings: this.$store.state.settings,
      address_informations: JSON.parse(
        this.$store.state.settings.address_informations
      ),
    };
  },
  mounted() {
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle: this.$t("contact.contact"),
      breadCrumbDescription: this.$t("contact.contact"),
    });
    this.$store.commit("SET_SLIDER", false);
    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
      }, 500);
    });
  },
  head(){
    return {
      title: this.$t("contact.contact"),
    }
  }
};
</script>