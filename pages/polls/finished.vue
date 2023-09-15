<template>
  <div class="col-sm-8">
    <FrontendPollsList :key="$route.fullPath" />
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  name: "Finished",
  mounted() {
    this.$store.commit("SET_SLIDER", false);
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle: this.$t("poll.finishedPolls"),
      breadCrumbDescription: this.$t("poll.finishedPolls"),
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
      title: this.$t("poll.finishedPolls"),
    }
  }
};
</script>