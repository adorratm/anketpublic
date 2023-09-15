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
                  >" {{ $t("panel.polls.pollVote") }}
                </h6>
                <p class="font-14 w-80">
                  {{ $t("panel.polls.pollsDesc") }}
                </p>
              </div>
              <div class="d-flex align-items-center">
                <a
                  v-if="parseInt($auth.user.role_id) === 1"
                  rel="dofollow"
                  href="javascript:void(0)"
                  @click.prevent="exportToExcelVoterList"
                  :title="$t('panel.polls.exportToExcelVoterList')"
                  class="btn btn-outline-success rounded-0 btn-sm me-2"
                  >{{ $t("panel.polls.exportToExcelVoterList") }}</a
                >
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
                  '/panel/poll-votes/datatable/' +
                  $route.params.id
                "
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
          label: this.$i18n.t("panel.polls.voter_username"),
          field: "voter_username",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.voter_answer"),
          field: "voter_answer",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.gender"),
          field: "gender",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.age"),
          field: "age",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.city"),
          field: "city",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.district"),
          field: "district",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.neighborhood"),
          field: "neighborhood",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.quarter"),
          field: "quarter",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.address"),
          field: "address",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.device"),
          field: "device",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.os"),
          field: "os",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.browser"),
          field: "browser",
          html: true,
          type: "string",
        },
        {
          label: this.$i18n.t("panel.polls.ip"),
          field: "ip",
          html: true,
          type: "string",
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
      ],
      sort: [
        {
          field: "id",
          type: "none",
        },
        {
          field: "voter_username",
          type: "none",
        },
        {
          field: "voter_answer",
          type: "none",
        },
        {
          field: "gender",
          type: "none",
        },
        {
          field: "age",
          type: "none",
        },
        {
          field: "city",
          type: "none",
        },
        {
          field: "district",
          type: "none",
        },
        {
          field: "neighborhood",
          type: "none",
        },
        {
          field: "quarter",
          type: "none",
        },
        {
          field: "address",
          type: "none",
        },
        {
          field: "device",
          type: "none",
        },
        {
          field: "os",
          type: "none",
        },
        {
          field: "browser",
          type: "none",
        },
        {
          field: "ip",
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
    async exportToExcelVoterList() {
      try {
        let { data } = await this.$axios.get(
          "panel/poll-votes/export-to-excel/" + this.$route.params.id
        );
        data.status
          ? this.$toast.success(data.message, this.$t("successfully"))
          : this.$toast.error(data.message, this.$t("unsuccessfully"));
        if (data && data.data) {
          window.open(data.data.url, "_blank");
        }
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("unsuccessfully"));
        }
        console.log(error);
      }
    },
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