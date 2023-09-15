<template>
  <ul :class="ismobile ? 'pt-newmobilemenu' : null">
    <li
      v-for="menu in topMenus"
      :key="menu.id"
      :class="
        (hasSubmenus(parentId) && getSubmenus(menu.id).length > 0) ||
        parseInt(menu.showCategories) === 1
          ? 'cats-links'
          : ''
      "
    >
      <nuxt-link
        rel="dofollow"
        :title="menu.title"
        :to="menu.url"
        :target="menu.target"
        >{{ menu.title }}</nuxt-link
      >
      <i
        class="fa fa-caret-down"
        v-if="hasSubmenus(menu.id) || parseInt(menu.showCategories) === 1"
      ></i>
      <ul class="dropdown" v-if="parseInt(menu.showCategories) === 1">
        <li v-for="(category, index) in categories" :key="index">
          <nuxt-link
            rel="dofollow"
            :title="category.title"
            :to="localePath('/categories/' + category.seo_url)"
          >
            <span class="bg-light"
              ><img
                v-if="category.icon"
                loading="lazy"
                width="30"
                height="30"
                :data-src="$config.UPLOADS_URL + 'categories/' + category.icon"
                class="lazyload img-fluid"
                :alt="category.title"
            /></span>
            {{ category.title }}
          </nuxt-link>
        </li>
        <li class="pl-not-found" v-if="!categories.length">Veri Bulunamadı</li>
      </ul>
      <!-- Include the <FrontendRecursiveMenuItem> component here for recursion -->

      <FrontendRecursiveMenuItem
        :class="{ dropdown: hasSubmenus(menu.id) }"
        :submenus="true"
        :isrecursive="false"
        v-if="hasSubmenus(menu.id)"
        :parentId="parseInt(menu.id)"
      />
    </li>
    <li class="pt-new-searchfield" v-if="!submenus">
      <input
        type="text"
        class="search"
        :placeholder="$t('poll.searchForPolls')"
        v-model="search"
        @keydown.enter="searchOnPolls"
      /><i class="icon-magnifier icons"></i>
      <div class="pl-search-result"></div>
    </li>

    <li v-if="this.$auth.loggedIn && !ismobile && !submenus" class="pl-newusr">
      <div class="pl-newuserdetails">
        <span class="show-user-details"
          ><img
            v-if="this.$auth.user.picture"
            loading="lazy"
            class="lazyload img-fluid"
            :data-src="
              this.$config.UPLOADS_URL + 'users/' + this.$auth.user.picture
            "
            :alt="this.$auth.user.first_name + ' ' + this.$auth.user.last_name"
          />
          <i v-else class="fa fa-user-circle text-dark fa-2x"></i>
          <small
            style="bottom: 0; right: 0; z-index: 1"
            class="badge rounded-circle bg-primary position-absolute w-auto p-1"
            v-if="this.$auth.user.type === 'CORPORATE'"
            data-bs-toggle="tooltip"
            data-bs-placement="right"
            :data-bs-title="$t('corporateAccount')"
            ><i class="text-white fa fa-check-circle m-0 p-0"></i
          ></small>
        </span>
        <ul class="dropdown">
          <li>
            <nuxt-link
              rel="dofollow"
              :title="$t('profile.myProfile')"
              :to="localePath('/profile')"
            >
              <i class="fa fa-user"></i> {{ $t("profile.myProfile") }}
              <span
                class="badge bg-primary w-auto px-0"
                v-if="this.$auth.user.type === 'CORPORATE'"
                data-bs-toggle="tooltip"
                data-bs-placement="right"
                :data-bs-title="$t('corporateAccount')"
                ><i class="text-white fa fa-check-circle mx-1"></i
              ></span>
            </nuxt-link>
          </li>
			<li>
            <nuxt-link
              rel="dofollow"
              :title="$t('profile.edit')"
              :to="localePath('/profile/edit')"
              ><i class="fa fa-user-edit"></i>
              {{ $t("profile.edit") }}</nuxt-link
            >
          </li>
          <li>
            <nuxt-link
              rel="dofollow"
              :title="$t('profile.myPolls')"
              :to="localePath('/profile/my-polls')"
              ><i class="fa fa-question"></i>
              {{ $t("profile.myPolls") }}</nuxt-link
            >
          </li>
          <li>
            <nuxt-link
              rel="dofollow"
              :title="$t('profile.myFollowings')"
              :to="localePath('/profile/my-followings')"
              ><i class="fa fa-user-plus"></i>
              {{ $t("profile.myFollowings") }}</nuxt-link
            >
          </li>
          <li>
            <nuxt-link
              rel="dofollow"
              :title="$t('profile.myFollowers')"
              :to="localePath('/profile/my-followers')"
              ><i class="fa fa-users"></i>
              {{ $t("profile.myFollowers") }}</nuxt-link
            >
          </li>

          <li v-if="parseInt($auth.user.role_id) !== 3">
            <a
              rel="dofollow"
              :href="localePath('/panel')"
              :title="$t('managementPanel')"
              ><i class="fa fa-cogs"></i> {{ $t("managementPanel") }}</a
            >
          </li>
          <li>
            <a
              rel="dofollow"
              :title="$t('logout')"
              :href="localePath('/')"
              @click.prevent="logout"
              class="logout"
              ><i class="fa fa-power-off"></i> {{ $t("logout") }}</a
            >
          </li>
        </ul>
      </div>
    </li>
    <!-- End User Details -->

    <li class="pt-new-createbtn" v-if="!submenus && !ismobile">
      <nuxt-link
        rel="dofollow"
        :title="$t('createPoll')"
        v-if="this.$auth.loggedIn"
        class="nbtn green-btn"
        :to="localePath('/polls/create')"
        ><i class="fa fa-edit"></i> {{ $t("createPoll") }}</nuxt-link
      >

      <nuxt-link
        rel="dofollow"
        :title="$t('login.login')"
        v-else
        class="nbtn green-btn"
        to="javascript:void(0)"
        data-bs-toggle="modal"
        data-bs-target="#sign-modal"
        ><i class="fa fa-lock"></i> {{ $t("login.login") }}</nuxt-link
      >
    </li>
    <li class="pt-newmobilebtn" v-if="!submenus && !ismobile">
      <a
        rel="dofollow"
        title="Menu"
        class="nbtn blue-btn"
        href="javascript:void(0)"
        ><i class="fa fa-bars"></i
      ></a>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    submenus: {
      type: Boolean,
    },
    isrecursive: {
      type: Boolean,
      default: true,
    },
    ismobile: {
      type: Boolean,
      default: false,
    },
    parentId: {
      type: Number,
      required: true,
    },
  },
  watch: {
    "$store.state.auth.user.picture": {
      immediate: true, // İlk başta da değeri izle
      handler(newVal) {
        this.updateProfileImage(newVal); // Profil görüntüsünü güncelle
      },
    },
  },
  computed: {
    menus() {
      return this.$store.state.menus;
    },
    categories() {
      return this.$store.state.categories;
    },
  },
  data() {
    return {
      topMenus: [],
      search: "",
    };
  },
  mounted() {
    this.topMenus = this.menus.filter(
      (menu) => parseInt(menu.top_id) === parseInt(this.parentId)
    );
  },
  methods: {
    logout() {
      try {
        this.$auth.logout();
        this.$auth.$storage.removeUniversal("user");
        this.$auth.$storage.removeUniversal("admin");
        this.$auth.strategy.refreshToken.reset();
        this.$auth.strategy.token.reset();
        this.$auth.reset();
        this.$axios.setHeader("Authorization", false);
        this.$router.replace(this.localePath("/") + "?logout=true").then(() => {
          this.$toast.success(
            this.$t("logoutSuccessfully"),
            this.$t("successfully")
          );
        });
      } catch (error) {
        console.log(error);
      }
    },
    searchOnPolls() {
      if (
        this.$route.path === "/polls" ||
        this.$route.path.startsWith("/categories/")
      ) {
        this.$router.push({
          query: { search: this.search },
        });
      } else {
        this.$router.push({
          path: this.localePath("/polls"),
          query: { search: this.search },
        });
      }
    },
    updateProfileImage(newVal) {
      this.$nextTick(() => {
        if (this.$refs.lazyimage) {
          this.$refs.lazyimage.src = this.$auth.user.picture.startsWith("http")
            ? this.$auth.user.picture
            : this.$config.API_URL + "uploads/users/" + newVal;
        }
      });
    },
    getSubmenus(parentId) {
      return this.menus.filter(
        (menu) => parseInt(menu.top_id) === parseInt(parentId)
      );
    },
    hasSubmenus(parentId) {
      return this.menus.some(
        (menu) => parseInt(menu.top_id) === parseInt(parentId)
      );
    },
  },
};
</script>