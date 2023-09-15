<template>
  <div>
    <!-- Container -->
    <div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
      <!-- Row -->
      <div class="row">
        <div class="col-xl-12">
          <div class="card card-refresh">
            <div class="refresh-container">
              <div class="loader-pendulums"></div>
            </div>
            <div class="card-header card-header-action">
              <div class="flex-grow-1">
                <h6 class="mb-10">
                  {{ $t("panel.polls.polls") }}
                </h6>
                <p class="font-14 w-80">
                  {{ $t("panel.polls.pollsDesc") }}
                </p>
              </div>
              <div class="d-flex align-items-center">
                <nuxt-link
                  rel="dofollow"
                  :title="$t('panel.goBack')"
                  class="btn btn-sm btn-outline-primary rounded-0"
                  :to="localePath('/panel/polls/')"
                  >{{ $t("panel.goBack") }}</nuxt-link
                >
              </div>
            </div>
            <div class="card-body">
              <BackendPollsStatisticsContent :id="this.$route.params.id" />
            </div>
          </div>
        </div>
      </div>
      <!-- /Row -->
    </div>
    <!-- /Container -->
  </div>
</template>
<script>
export default {
  layout: "admin_layout",
  mounted() {
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