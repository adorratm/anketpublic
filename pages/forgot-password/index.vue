<template>
  <div class="col-lg-8">
    <div class="pl-main">
      <FrontendForgotPasswordForm />
    </div>
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  middleware: ["auth", "unauthenticated"],
  mounted() {
    this.$store.commit("SET_SLIDER", false);
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle: this.$t("forgotPassword.forgotPassword"),
      breadCrumbDescription: this.$t("forgotPassword.forgotPassword"),
    });
    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $("#sign-modal").modal("hide");
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
      }, 500);
    });
  },
  head() {
    return {
      bodyAttrs: {
        class: "pl-body-register",
      },
      title: this.$t("forgotPassword.forgotPassword"),
    };
  },
};
</script>