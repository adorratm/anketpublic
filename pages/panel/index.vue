<template>
  <div></div>
</template>

<script>
export default {
  layout: "admin_layout",
  mounted() {
    this.$router.push(this.localePath("/panel/settings"));
    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
        $(".hk-wrapper").removeClass("d-none");
      }, 500);
    });
  },
};
</script>