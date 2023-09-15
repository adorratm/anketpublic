<template>
  <div>
    <ssr-carousel
      class="mb-4"
      v-if="slides.length"
      :autoplay-delay="3"
      loop
      show-arrows
      show-dots
    >
      <div class="slide" :key="slide.id" v-for="(slide, index) in slides">
        <nuxt-link
          rel="dofollow"
          :to="slide.url"
          :target="slide.target"
          :title="slide.title"
          class="d-block"
        >
          <img
            width="1920"
            height="700"
            loading="lazy"
            class="img-fluid lazyload w-100"
            :data-src="$config.UPLOADS_URL + 'slides/' + slide.image"
            :alt="slide.title"
          />
        </nuxt-link>
      </div>
    </ssr-carousel>
  </div>
</template>

<script>
export default {
  data() {
    return {
      slides: [],
    };
  },
  mounted() {
    this.getSlides();
  },
  methods: {
    async getSlides() {
      const { data } = await this.$axios.$get("slides");
      this.slides = data.map((element) => {
        if (element.page_url) {
          element.url = "/pages/" + element.page_url;
        }
        if (element.category_url) {
          element.url = "/categories/" + element.category_url;
        }
        if (element.poll_url) {
          element.url = "/polls/" + element.poll_url;
        }
        return element;
      });
    },
  },
};
</script>
