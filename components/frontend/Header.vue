<template>
  <div class="pl-new-header">
    <div class="container-fluid">
      <div class="pl-new-topheader">
        <FrontendRecursiveMenuItem
          :ismobile="true"
          :parentId="0"
          :isrecursive="true"
        />
        <div class="pt-new-logo">
          <nuxt-link rel="dofollow" :title="$t('home')" :to="localePath('/')"
            ><img
              loading="lazy"
              :data-src="
                this.$config.UPLOADS_URL +
                'settings/' +
                this.$store.state.settings.logo
              "
              class="lazyload img-fluid"
              :alt="$t('home')"
          /></nuxt-link>
        </div>
        <FrontendRecursiveMenuItem
          :ismobile="false"
          :parentId="0"
          :isrecursive="true"
        />
      </div>
    </div>
  </div>
</template>