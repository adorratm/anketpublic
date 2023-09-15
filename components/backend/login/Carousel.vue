<template>
  <div>
    <ssr-carousel
      :slides-per-page='1'
      :autoplay-delay="5"
      loop
      show-arrows
    >
      <div
        class="slide fadeOut item auth-cover-img overlay-wrap"
        style="background-image: url(/backend/img/background.webp)"
      >
        <div class="auth-cover-info py-xl-0 pt-100 pb-50">
          <div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">
            <h1 class="display-3 text-white mb-20">
              {{ $t("panel.manageYourWebsiteBetter") }}
            </h1>
            <p class="text-white">
              {{ $t("panel.manageYourWebsiteBetterDescription") }}
            </p>
          </div>
        </div>
        <div class="bg-overlay bg-trans-dark-75"></div>
      </div>
      <div
        class="slide fadeOut item auth-cover-img overlay-wrap"
        style="background-image: url(/backend/img/background.webp)"
      >
        <div class="auth-cover-info py-xl-0 pt-100 pb-50">
          <div class="auth-cover-content text-center w-xxl-75 w-sm-90 w-xs-100">
            <h1 class="display-3 text-white mb-20">
              {{ $t("panel.manageYourWebsiteBetter") }}
            </h1>
            <p class="text-white">
              {{ $t("panel.manageYourWebsiteBetterDescription") }}
            </p>
          </div>
        </div>
        <div class="bg-overlay bg-trans-dark-75"></div>
      </div>
    </ssr-carousel>
  </div>
</template>