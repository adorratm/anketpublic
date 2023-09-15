<template>
  <div>
    <div class="pl-box mb-3 p-3">
      <div class="pl-content">
        <div class="input-group">
          <input
            type="text"
            class="form-control form-control-sm rounded-0"
            v-model="searchTerm"
            @keydown.enter="$refs.searchButton.click()"
            :placeholder="$t('categories.search')"
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
            @click.prevent="getCategories"
          >
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </div>
    <div
      class="row align-items-stretch align-self-stretch align-content-stretch"
      v-if="categories"
    >
      <div class="col-sm-4 mb-3" v-for="item in categories" :key="item.id">
        <div class="pl-box h-100">
          <div class="pl-content p-3">
            <div class="border-bottom pb-3 text-center">
              <nuxt-link
                :to="localePath('/categories/' + item.seo_url)"
                :title="item.title"
                rel="dofollow"
              >
                <img
                  loading="lazy"
                  :data-src="$config.UPLOADS_URL + '/categories/' + item.icon"
                  :alt="item.title"
                  class="lazyload rounded-circle p-3 bg-light img-fluid"
                  width="125"
                />
              </nuxt-link>
            </div>
          </div>
          <div class="pl-content pb-3">
            <h3 class="text-center fw-bold text-blue mb-0">
              <nuxt-link
                class="text-blue"
                :to="localePath('/categories/' + item.seo_url)"
                :title="item.title"
                rel="dofollow"
              >
                {{ item.title }}
              </nuxt-link>
            </h3>
          </div>
        </div>
      </div>
      <div class="col-sm-12 mt-3" v-if="recordsFiltered > categories.length">
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center text-center">
            <li :class="['page-item', { disabled: currentPage === 1 }]">
              <a
                rel="dofollow"
                class="page-link"
                @click.prevent="(currentPage -= 1), getCategories()"
                href="javascript:void(0)"
                aria-label="Previous"
                title="Önceki Sayfa"
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
                  getCategories();
                "
                href="javascript:void(0)"
                >{{ page }}</a
              >
            </li>
            <li :class="['page-item', { disabled: currentPage === totalPages }]">
              <a
                rel="dofollow"
                title="Sonraki Sayfa"
                class="page-link"
                @click="(currentPage += 1), getCategories()"
                href="javascript:void(0)"
                aria-label="Next"
              >
                <span aria-hidden="true" class="fa fa-angle-double-right"></span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CategoriesList",
  data() {
    return {
      searchTerm: this.$route.query.search || "",
      recordsFiltered: 0,
      recordsTotal: 0,
      categories: [],
      perPage: 12,
      totalPages: 0,
      currentPage: 1,
    };
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    async getCategories() {
      try {
        const { data } = await this.$axios.get("categories-list", {
          params: {
            searchTerm: this.searchTerm,
            start: this.currentPage - 1,
            perPage: this.perPage,
            page: this.currentPage,
          },
        });
        this.categories = data.data;
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
      title: this.$t("categories.categories"),
    };
  },
};
</script>
