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
  data() {
    return {
      searchTerm: this.$route.query.search || "",
      recordsFiltered: 0,
      recordsTotal: 0,
      polls: [],
      perPage: 12,
      totalPages: 0,
      currentPage: 1,
    };
  },
  async asyncData({ params, $axios }) {
    try {
      const url = "poll/" + params.id;
      const { data } = await $axios.get(url);

      const polls = data.data.map((value) => {
        value.images = value.images ? JSON.parse(value.images) : null;
        return value;
      });

      return {
        polls,
      };
    } catch (error) {
      console.error(error);
    }
  },
  head() {
    return {
      title: this.polls[0]?.title,
      meta: [
        {
          hid: "description",
          name: "description",
          content: this.polls[0]?.description,
        },
        {
          hid: "og:title",
          name: "og:title",
          content: this.polls[0]?.title,
        },
        {
          hid: "og:description",
          name: "og:description",
          content: this.polls[0]?.description,
        },
        {
          hid: "og:url",
          name: "og:url",
          content: this.$config.BASE_URL.slice(0, -1) + this.$route.fullPath,
        },
        {
          hid: "og:image",
          name: "og:image",
          content: this.polls[0]?.images?.length
            ? this.$config.UPLOADS_URL + "/polls/" + this.polls[0].images[0]
            : this.$config.BASE_URL + "settings/" + this.$store.state.settings.logo,
        },
        {
          hid: "og:image:secure_url",
          name: "og:image:secure_url",
          content: this.polls[0]?.images?.length
            ? this.$config.UPLOADS_URL + "/polls/" + this.polls[0].images[0]
            : this.$config.BASE_URL + "settings/" + this.$store.state.settings.logo,
        },
        {
          hid: "og:image:alt",
          name: "og:image:alt",
          content: this.polls[0]?.title,
        },
        {
          hid: "twitter:title",
          name: "twitter:title",
          content: this.polls[0]?.title,
        },
        {
          hid: "twitter:description",
          name: "twitter:description",
          content: this.polls[0]?.description,
        },
        {
          hid: "twitter:image",
          name: "twitter:image",
          content: this.polls[0]?.images?.length
            ? this.$config.UPLOADS_URL + "/polls/" + this.polls[0].images[0]
            : this.$config.BASE_URL + "settings/" + this.$store.state.settings.logo,
        },
        {
          hid: "twitter:image:alt",
          name: "twitter:image:alt",
          content: this.polls[0]?.title,
        },
        {
          hid: "twitter:card",
          name: "twitter:card",
          content: "summary_large_image",
        },
        {
          hid: "twitter:domain",
          name: "twitter:domain",
          content: this.$config.BASE_URL,
        },
        {
          hid: "twitter:url",
          name: "twitter:url",
          content: this.$config.BASE_URL.slice(0, -1) + this.$route.fullPath,
        },
      ],
    };
  },
};
</script>
