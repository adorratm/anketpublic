<template>
  <div class="col-lg-8">
    <FrontendPollsList :key="$route.fullPath" :data_url="'polls-list'" :isfrontend="true" />
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  mounted() {
    this.$store.commit("SET_BREADCRUMB", { showBreadCrumb: false });
    this.$store.commit("SET_SLIDER", true);
    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
      }, 500);
    });
  },
  head() {
    return {
      title: this.$t("homepage"),
      meta: [
        {
          hid: "description",
          name: "description",
          content: this.$store.state.settings.meta_description,
        },
      ]
    };
  },
};
</script>
