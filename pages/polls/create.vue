<template>
  <div class="col-sm-8">
    <div class="pl-main">
      <div class="pl-signup">
        <h4 class="pl-page-head">
          {{ $t("createPoll") }}
          <i class="fa fa-question-circle"></i>
        </h4>
        <FrontendPollsContent :id="id" />
      </div>
    </div>
    <!-- End Main -->
  </div>
</template>


<script>
export default {
  layout: "frontend_layout",
  name: "CreatePoll",
  middleware: ["auth", "authenticated"],
  data() {
    return {
      id: null,
    };
  },
  mounted() {
    if (this.$route.params.id) {
      this.id = this.$route.params.id;
    }
    if (parseInt(this.$auth.user.role_id) === 1) {
      this.$router.replace(this.localePath("/")).then(() => {
        this.$toast.error(this.$t("unAuthorized"), this.$t("unsuccessfully"));
      });
    }
    this.$store.commit("SET_SLIDER", false);
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle: this.$t("createPoll"),
      breadCrumbDescription: this.$t("createPollDescription"),
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
      title: this.$t("createPoll"),
    }
  }
};
</script>