<template>
  <div class="col-sm-8">
    <FrontendPollsList :seo_url="this.$route.params.id" />
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  name: "PollDetail",
  mounted() {
    this.$store.commit("SET_SLIDER", false);
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle: this.$t("poll.polls"),
      breadCrumbDescription: this.$t("poll.polls"),
    });
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
      title: this.$t("poll.polls"),
    }
  }
};
</script>