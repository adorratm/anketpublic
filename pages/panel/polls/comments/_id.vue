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
                  "<b class="fw-bolder">{{ pollData?.title }}</b
                  >" {{ $t("panel.polls.pollComment") }}
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
              <BackendDatatable
                :dataurl="
                  $config.API_URL +
                  '/panel/poll-comments/datatable/' +
                  $route.params.id
                "
                :isactiveurl="
                  $config.API_URL + '/panel/poll-comments/isactive/'
                "
                :deleteurl="$config.API_URL + '/panel/poll-comments/delete/'"
                :token="this.$auth.strategy.token.get()"
                :columns="columns"
                :sort="sort"
              />
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
  data() {
    return {
      pollData: null,
      columns: [
        {
          label: this.$i18n.t("panel.id"),
          field: "id",
          html: true,
          width: "65px",
          type: "number",
        },
        {
          label: this.$i18n.t("panel.polls.commenter_username"),
          field: "commenter_username",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.comment"),
          field: "comment",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.lang"),
          field: "lang",
          html: true,
          width: "60px",
          type: "string",
        },
        {
          label: this.$i18n.t("panel.isActive"),
          field: "isActive",
          html: true,
          type: "boolean",
        },
        {
          label: this.$i18n.t("panel.createdAt"),
          field: "createdAt",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.updatedAt"),
          field: "updatedAt",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.actions"),
          field: "actions",
          html: true,
          globalSearchDisabled: true,
          sortable: false,
        },
      ],
      sort: [
        {
          field: "id",
          type: "none",
        },
        {
          field: "commenter_username",
          type: "none",
        },
        {
          field: "comment",
          type: "none",
        },
        {
          field: "lang",
          type: "none",
        },
        {
          field: "isActive",
          type: "none",
        },
        {
          field: "createdAt",
          type: "none",
        },
        {
          field: "updatedAt",
          type: "none",
        },
      ],
    };
  },
  mounted() {
    this.getPoll(this.$route.params.id);
    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
        $(".hk-wrapper").removeClass("d-none");
      }, 500);
    });
  },
  methods: {
    async getPoll(id) {
      try {
        let { data } = await this.$axios.get("panel/poll/" + id);
        if (data && data.data) {
          this.pollData = data.data[0];
          this.pollData.images = this.pollData.images
            ? JSON.parse(this.pollData.images)
            : [];
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>