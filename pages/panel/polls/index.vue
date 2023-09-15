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
              <div class="d-flex align-items-center" v-if="parseInt($auth.user.role_id) === 1">
                <nuxt-link
                  rel="dofollow"
                  :title="$t('panel.createNew')"
                  class="btn btn-sm btn-outline-primary rounded-0"
                  :to="localePath('/panel/polls/add')"
                  >{{ $t("panel.createNew") }}</nuxt-link
                >
              </div>
            </div>
            <div class="card-body">
              <BackendDatatable
                :dataurl="$config.API_URL + '/panel/polls/datatable'"
                :rankurl="$config.API_URL + '/panel/polls/rank/'"
                :isactiveurl="$config.API_URL + '/panel/polls/isactive/'"
                :pinnedurl="$config.API_URL + '/panel/polls/pinned/'"
                :editurl="'/panel/polls/update/'"
                :deleteurl="$config.API_URL + '/panel/polls/delete/'"
                :token="this.$auth.strategy.token.get()"
                :columns="columns"
                :sort="sort"
                :customActions="customActions"
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
      columns: [
        {
          label: this.$i18n.t("panel.rank"),
          field: "rank",
          html: true,
          width: "125px",
          type: "number",
        },
        {
          label: this.$i18n.t("panel.id"),
          field: "id",
          html: true,
          width: "65px",
          type: "number",
        },
        {
          label: this.$i18n.t("panel.polls.title"),
          field: "title",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.author"),
          field: "author",
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
          label: this.$i18n.t("panel.pinned"),
          field: "pinned",
          html: true,
          type: "boolean",
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
          field: "rank",
          type: "none",
        },
        {
          field: "id",
          type: "none",
        },
        {
          field: "title",
          type: "none",
        },
        {
          field: "lang",
          type: "none",
        },
        {
          field: "pinned",
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
      customActions: [],
    };
  },
  mounted() {
    this.customActions = [
      {
        label: this.$t("panel.polls.pollVotes"),
        icon: "fa fa-thumbs-up me-2",
        url: "/panel/polls/votes/",
      },
      {
        label: this.$t("panel.polls.pollComments"),
        icon: "fa fa-comments me-2",
        url: "/panel/polls/comments/",
      },
      {
        label: this.$t("panel.polls.pollStatistics"),
        icon: "fas fa-chart-bar me-2",
        url: "/panel/polls/statistics/",
      },
    ];
    if (parseInt(this.$auth.user.role_id) === 1) {
      this.customActions.push({
        label: this.$t("panel.polls.addFakeVotes"),
        icon: "fas fa-poll me-2",
        url: "/panel/polls/add-fake-votes/",
      });
    }
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