<template>
  <header class="d-flex justify-content-between align-items-center">
    <nuxt-link
      :to="localePath('/panel/')"
      rel="dofollow"
      :title="$t('panel.adorratm')"
      class="d-flex auth-brand"
    >
      <img
        class="brand-img"
        src="https://avatars.githubusercontent.com/u/39022587"
        :alt="$t('panel.adorratm')"
        width="50"
        height="50"
      />
    </nuxt-link>
    <div class="btn-group btn-group-sm">
      <a
        rel="dofollow"
        :title="$t('panel.contact')"
        href="https://linkedin.com/in/emrekilic98"
        target="_blank"
        class="btn btn-outline-secondary"
        >{{ $t("panel.contact") }}</a
      >
      <a
        rel="dofollow"
        :title="$t('panel.aboutUs')"
        href="https://linkedin.com/in/emrekilic98"
        target="_blank"
        class="btn btn-outline-secondary"
        >{{ $t("panel.aboutUs") }}</a
      >
    </div>
  </header>
</template>