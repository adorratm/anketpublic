<template>
  <div>
    <div
      class="pt-new-homebreadcrump pt-new-pagebreadcrump"
      :style="
        'background:url(' +
        $config.UPLOADS_URL +
        '/settings/' +
        $store.state.settings.banner +
        ')'
      "
    >
      <div class="container">
        <h3>{{ title }}</h3>
        <p>
          <nuxt-link rel="dofollow" :title="$t('home')" :to="localePath('/')">{{
            $t("home")
          }}</nuxt-link>
          <i class="fa fa-arrow-circle-right"></i> {{ description }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title", "description"],
};
</script>