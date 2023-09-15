<template>
  <div class="col-sm-8">
    <FrontendPage :seourl="$route.params.id" />
  </div>
</template>

<script>
export default {
  name: "Page",
  layout: "frontend_layout",

  head() {
    return {
      bodyAttrs: {
        class: "pl-body-profile",
      },
    };
  },
  mounted() {
    this.$store.commit("SET_SLIDER", false);

    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
      }, 500);
    });
  },
};
</script>