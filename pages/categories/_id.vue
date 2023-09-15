<template>
  <div class="col-sm-8">
    <FrontendPollsList v-if="$route.params.id" :category="$route.params.id" />
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  name: "CategoryDetail",
  mounted() {
    this.getCategory();

    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
      }, 500);
    });
  },
  data() {
    return {
      category: {},
    };
  },
  methods: {
    async getCategory() {
      try {
        const { data } = await this.$axios.get(
          `/categories/${this.$route.params.id}`
        );
        this.category = data.data;
        this.$store.commit("SET_SLIDER", false);
        this.$store.commit("SET_BREADCRUMB", {
          showBreadCrumb: true,
          breadCrumbTitle: this.category?.title,
          breadCrumbDescription: this.category?.title,
        });
      } catch (error) {
        console.log(error);
      }
    },
  },
  head(){
    return {
      title: this.category?.title,
    }
  }
};
</script>