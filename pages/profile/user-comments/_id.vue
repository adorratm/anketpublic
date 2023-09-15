<template>
  <div class="col-lg-8">
    <div class="pl-box mb-3 p-3">
      <div class="pl-content">
        <div class="input-group">
          <input
            type="text"
            class="form-control form-control-sm rounded-0"
            v-model="searchTerm"
            @keydown.enter="$refs.searchButton.click()"
            :placeholder="$t('comments.search')"
            @keydown="
              $router.push({
                query: { ...$route.query, search: searchTerm },
              })
            "
          />
          <button
            v-if="searchTerm"
            class="btn bg-danger text-white"
            type="button"
            @click.prevent="
              searchTerm = null;
              $refs.searchButton.click();
              $router.push({
                query: { ...$route.query, search: null },
              });
            "
          >
            <i class="fa fa-times"></i>
          </button>
          <button
            ref="searchButton"
            class="btn bg-blue text-white"
            type="button"
            @click.prevent="getUserComments"
          >
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </div>
    <div
      class="row align-items-stretch align-self-stretch align-content-stretch"
      v-if="userComments"
    >
      <div class="col-sm-12 mb-3" v-for="item in userComments" :key="item.id">
        <div class="pl-box h-100">
          <div class="pl-content p-3 border-bottom pb-3 text-center">
            <div
              class="d-flex align-items-center align-self-center align-content-center"
            >
              <nuxt-link
                :to="localePath('/poll/' + item.poll_seo_url)"
                :title="item.poll_title"
                rel="dofollow"
                class="mx-auto text-dark fw-bold"
              >
                {{ item.poll_title }} {{ $t("comments.pollComment") }}
              </nuxt-link>
            </div>
          </div>
          <div class="d-flex flex-row pl-comment px-3">
            <div class="p-2">
              <div
                class="pl-thumb text-center d-flex align-items-center align-self-center align-content-center"
              >
                <nuxt-link
                  rel="dofollow"
                  :title="item.commenter_username"
                  :to="localePath('/profile/' + item.commenter_username)"
                  class="d-flex align-items-center align-self-center align-content-center text-center mx-auto"
                >
                  <img
                    v-if="item.commenter_photo"
                    loading="lazy"
                    class="lazyload img-fluid"
                    :data-src="
                      $config.UPLOADS_URL + '/users/' + item.commenter_photo
                    "
                    :alt="item.commenter_username"
                  />
                  <i
                    class="fa fa-2x fa-user-circle text-center mx-auto"
                    v-else
                  ></i>
                </nuxt-link>
              </div>
            </div>
            <div class="p-2 flex-grow-1">
              <div class="pt-commentbox">
                <div class="pl-title">
                  <nuxt-link
                    rel="dofollow"
                    :title="item.commenter_username"
                    :to="localePath('/profile/' + item.commenter_username)"
                    >{{ item.commenter_username }}</nuxt-link
                  >
                  <small class="float-end">{{ item.updatedAt }}</small>
                </div>
                <div class="pl-cmt-content">{{ item.comment }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 mt-3" v-if="recordsFiltered > userComments.length">
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center text-center">
            <li :class="['page-item', { disabled: currentPage === 1 }]">
              <a
                rel="dofollow"
                title="Önceki Sayfa"
                class="page-link"
                @click.prevent="(currentPage -= 1), getUserComments()"
                href="javascript:void(0)"
                aria-label="Previous"
              >
                <span aria-hidden="true" class="fa fa-angle-double-left"></span>
              </a>
            </li>
            <li
              :class="['page-item', { active: page === currentPage }]"
              v-for="page in totalPages"
              :key="page"
            >
              <a
                rel="dofollow"
                :title="page + '. Sayfa'"
                class="page-link"
                @click="
                  currentPage = page;
                  getUserComments();
                "
                href="javascript:void(0)"
                >{{ page }}</a
              >
            </li>
            <li
              :class="['page-item', { disabled: currentPage === totalPages }]"
            >
              <a
                rel="dofollow"
                title="Sonraki Sayfa"
                class="page-link"
                @click="(currentPage += 1), getUserComments()"
                href="javascript:void(0)"
                aria-label="Next"
              >
                <span
                  aria-hidden="true"
                  class="fa fa-angle-double-right"
                ></span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <div class="pl-box" v-else>
      <div class="pl-content">
        <div class="alert alert-danger">
          <div class="text-center">
            <i class="fa fa-5x fa-users mb-3"></i>
            <h5 class="text-blue mb-0">{{ $t("comments.notFound") }}</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "frontend_layout",
  middleware: ["auth", "authenticated"],
  data() {
    return {
      user: null,
      userComments: [],
      searchTerm: this.$route.query.search || null,
      recordsFiltered: 0,
      recordsTotal: 0,
      perPage: 15,
      totalPages: 0,
      currentPage: 1,
    };
  },
  mounted() {
    this.getUserComments();
    this.$store.commit("SET_SLIDER", false);
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle:
        this.$route.params.id + " " + this.$t("profile.userComments"),
      breadCrumbDescription:
        this.$route.params.id + " " + this.$t("profile.userComments"),
    });
    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
      }, 500);
    });
  },
  methods: {
    async getUserComments() {
      try {
        const { data } = await this.$axios.get(
          "user-comments/" + this.$route.params.id,
          {
            params: {
              searchTerm: this.searchTerm,
              start: this.currentPage - 1,
              perPage: this.perPage,
              page: this.currentPage,
            },
          }
        );
        this.userComments = data.data;
        this.recordsFiltered = data.recordsFiltered;
        this.recordsTotal = data.recordsTotal;
        this.totalPages = Math.ceil(data.recordsFiltered / this.perPage);
      } catch (error) {
        console.log(error);
      }
    },
  },
  head() {
    return {
      bodyAttrs: {
        class: "pl-body-profile",
      },
      title: this.$route.params.id + " " + this.$t("profile.userComments"),
    };
  },
};
</script>