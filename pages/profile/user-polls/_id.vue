<template>
  <div class="col-lg-8">
    <div class="pl-main">
      <FrontendPollsList
        :isprofile="true"
        :data_url="'user-polls/' + $route.params.id"
        :isfrontend="false"
        :key="$route.fullPath"
      />
    </div>
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  middleware: ["auth", "authenticated"],
  data() {
    return {
      user: this.$auth.user,
      statistics: null,
    };
  },
  mounted() {
    this.$store.commit("SET_SLIDER", false);
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle:
        this.$route.params.id + " " + this.$t("profile.userPolls"),
      breadCrumbDescription:
        this.$route.params.id + " " + this.$t("profile.userPolls"),
    });
    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
      }, 500);
    });
  },
  methods: {},
  head() {
    return {
      bodyAttrs: {
        class: "pl-body-profile",
      },
      title: this.$route.params.id + " " + this.$t("profile.userPolls"),
    };
  },
};
</script>