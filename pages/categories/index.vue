<template>
  <div class="col-sm-8">
    <FrontendCategoriesList ref="fcList" />
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  name: "Categories",
  mounted() {
    this.$store.commit("SET_SLIDER", false);
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle: this.$t("categories.categories"),
      breadCrumbDescription: this.$t("categories.categories"),
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
      title: this.$t("categories.categories"),
    }
  }
};
</script>