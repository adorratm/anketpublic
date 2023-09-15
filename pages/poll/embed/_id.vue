<template>
  <div class="col-sm-12">
    <FrontendPollsList :seo_url="this.$route.params.id" />
  </div>
</template>

<script>
export default {
  name: "PollDetail",
  layout: "frontend_embed_layout",
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
