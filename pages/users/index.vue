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
            :placeholder="$t('users.search')"
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
            @click.prevent="getUsers"
          >
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </div>
    <div
      class="row align-items-stretch align-self-stretch align-content-stretch"
      v-if="users"
    >
      <div class="col-sm-4 mb-3" v-for="item in users" :key="item.id">
        <div class="pl-box h-100">
          <div class="pl-content p-3">
            <div class="border-bottom pb-3 text-center">
              <nuxt-link
                :to="localePath('/profile/' + item.username)"
                :title="item.username"
                rel="dofollow"
              >
                <img
                  v-if="item.picture"
                  loading="lazy"
                  :data-src="$config.UPLOADS_URL + '/users/' + item.picture"
                  :alt="item.username"
                  class="lazyload rounded-circle p-3 bg-light img-fluid"
                  width="125"
                />
                <i
                  class="fa fa-3x fa-user-circle rounded-circle p-3 bg-light"
                  v-else
                ></i>
              </nuxt-link>
            </div>
          </div>
          <div class="pl-content pb-3">
            <h5 class="text-center fw-bold text-blue mb-0">
              <nuxt-link
                class="text-blue"
                :to="localePath('/profile/' + item.username)"
                :title="item.username"
                rel="dofollow"
              >
                {{ item.username }}
              </nuxt-link>
            </h5>
            <button
              class="nbtn green-btn mx-auto text-center d-flex mt-3 p-3"
              @click.prevent="followUnfollow(item.is_following, item.id)"
              :class="item.is_following ? 'bg-blue' : 'bg-0'"
            >
              {{
                item.is_following ? $t("users.unfollow") : $t("users.follow")
              }}
            </button>
          </div>
        </div>
      </div>
      <div class="col-sm-12 mt-3" v-if="recordsFiltered > users.length">
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center text-center">
            <li :class="['page-item', { disabled: currentPage === 1 }]">
              <a
                rel="dofollow"
                title="Önceki Sayfa"
                class="page-link"
                @click.prevent="(currentPage -= 1), getUsers()"
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
                  getUsers();
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
                @click="(currentPage += 1), getUsers()"
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
            <h5 class="text-blue mb-0">{{ $t("users.notFound") }}</h5>
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
      user: this.$auth.user,
      users: [],
      searchTerm: this.$route.query.search || null,
      recordsFiltered: 0,
      recordsTotal: 0,
      perPage: 15,
      totalPages: 0,
      currentPage: 1,
    };
  },
  mounted() {
    this.getUsers();
    this.$store.commit("SET_SLIDER", false);
    this.$store.commit("SET_BREADCRUMB", {
      showBreadCrumb: true,
      breadCrumbTitle: this.$t("users.users"),
      breadCrumbDescription: this.$t("users.users"),
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
    async followUnfollow(is_following, id) {
      try {
        let url = is_following ? "unfollow-user" : "follow-user";
        const { data } = await this.$axios.post(url, {
          user_id: this.user.id,
          following_id: id,
        });
        if (data.status) {
          this.$toast.success(data.message, data.title);
          this.getUsers();
        } else {
          this.$toast.error(data.message, data.title);
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getUsers() {
      try {
        const { data } = await this.$axios.get("users", {
          params: {
            searchTerm: this.searchTerm,
            start: this.currentPage - 1,
            perPage: this.perPage,
            page: this.currentPage,
          },
        });
        this.users = data.data;
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
      title: this.$t("users.users"),
    };
  },
};
</script>