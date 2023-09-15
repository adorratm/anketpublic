<template>
  <div>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-xl navbar-dark fixed-top hk-navbar">
      <a
        id="navbar_toggle_btn"
        class="navbar-toggle-btn nav-link-hover"
        href="javascript:void(0);"
        title="Toggle Menu"
        ><i class="fa fa-bars"></i
      ></a>
      <nuxtLink class="navbar-brand" :to="localePath('/panel')">
        <img
          class="brand-img d-inline-block rounded-circle"
          src="https://avatars.githubusercontent.com/u/39022587"
          alt="Adorratm"
          width="50"
          height="50"
        />
      </nuxtLink>
      <ul class="navbar-nav hk-navbar-content">
        <li
          class="nav-item dropdown dropdown-notifications"
          v-if="notifications.length"
        >
          <a
            rel="dofollow"
            class="nav-link dropdown-toggle no-caret"
            href="javascript:void(0)"
            role="button"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            title="Notifications"
            ><i class="fa fa-bell"></i
            ><span class="badge-wrap"
              ><span
                class="badge badge-primary badge-indicator badge-indicator-sm badge-pill pulse"
              ></span></span
          ></a>
          <div
            class="dropdown-menu dropdown-menu-end"
            data-dropdown-in="fadeIn"
            data-dropdown-out="fadeOut"
          >
            <h6 class="dropdown-header">
              Notifications
              <a rel="dofollow" href="javascript:void(0);">View all</a>
            </h6>
            <div class="notifications-nicescroll-bar">
              <a
                rel="dofollow"
                href="javascript:void(0);"
                title="Notification"
                class="dropdown-item"
              >
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <div class="avatar avatar-sm">
                      <span
                        class="avatar-text avatar-text-warning rounded-circle"
                      >
                        <span class="initial-wrap"
                          ><span><i class="fa fa-bell"></i></span
                        ></span>
                      </span>
                    </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <div>
                      <div class="notifications-text">
                        Last 2 days left for the project
                      </div>
                      <div class="notifications-time">15d</div>
                    </div>
                  </div>
                </div>
              </a>
              <div class="dropdown-divider"></div>
            </div>
          </div>
        </li>
        <li class="nav-item dropdown dropdown-authentication">
          <a
            rel="dofollow"
            class="nav-link dropdown-toggle no-caret"
            href="javascript:void(0)"
            role="button"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            :title="$t('panel.profile')"
          >
            <div
              class="d-flex align-items-center align-self-center align-content-center"
            >
              <div
                class="flex-shrink-0 d-flex align-items-center align-self-center align-content-center"
              >
                <img
                  ref="lazyimage"
                  v-if="$auth.user.picture"
                  loading="lazy"
                  :data-src="
                    $auth.user.picture.startsWith('http')
                      ? $auth.user.picture
                      : $config.API_URL + 'uploads/users/' + $auth.user.picture
                  "
                  class="lazyload fs-5 rounded-circle"
                  :alt="$auth.user.first_name + ' ' + $auth.user.last_name"
                  width="35"
                  height="35"
                  referrerpolicy="no-referrer"
                />
                <i v-else class="fa fa-user-circle fs-5"></i>
                <span class="badge badge-success badge-indicator"></span>
              </div>
              <div class="flex-grow-1 ms-3">
                <span
                  >{{ this.$auth.user.first_name }}
                  {{ this.$auth.user.last_name
                  }}<i class="fa fa-chevron-down"></i
                ></span>
              </div>
            </div>
          </a>
          <div
            class="dropdown-menu dropdown-menu-end"
            data-dropdown-in="flipInX"
            data-dropdown-out="flipOutX"
          >
            <nuxt-link
              rel="dofollow"
              :title="$t('panel.profile')"
              :to="localePath('/panel/users/update/' + this.$auth.user.id)"
              class="dropdown-item d-flex align-items-center align-self-center align-content-center"
            >
              <i class="dropdown-icon fa fa-user"></i
              ><span>{{ $t("panel.profile") }}</span></nuxt-link
            >
            <nuxt-link
              rel="dofollow"
              :title="$t('panel.settings.settings')"
              class="dropdown-item d-flex align-items-center align-self-center align-content-center"
              :to="localePath('/panel/settings/')"
              ><i class="dropdown-icon fa fa-cogs"></i
              ><span>{{ $t("panel.settings.settings") }}</span></nuxt-link
            >
            <div class="dropdown-divider"></div>
            <a
              rel="dofollow"
              class="dropdown-item d-flex align-items-center align-self-center align-content-center"
              :href="localePath('/panel/')"
              @click.prevent="logout"
              :title="$t('panel.logout')"
            >
              <i class="dropdown-icon fa fa-power-off"></i>
              <span>{{ $t("panel.logout") }}</span>
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /Top Navbar -->
  </div>
</template>

<script>
export default {
  watch: {
    "$store.state.auth.user.picture": {
      immediate: true, // İlk başta da değeri izle
      handler(newVal) {
        this.updateProfileImage(newVal); // Profil görüntüsünü güncelle
      },
    },
  },
  data() {
    return {
      notifications: [],
    };
  },
  methods: {
    updateProfileImage(newVal) {
      this.$nextTick(() => {
        if (this.$refs.lazyimage) {
          this.$refs.lazyimage.src =
            this.$config.API_URL + "uploads/users/" + newVal;
        }
      });
    },
    logout() {
      try {
        this.$auth.logout();
        this.$auth.$storage.removeUniversal("user");
        this.$auth.$storage.removeUniversal("admin");
        this.$auth.strategy.refreshToken.reset();
        this.$auth.strategy.token.reset();
        this.$auth.reset();
        this.$axios.setHeader("Authorization", false);
        this.$router.replace(this.localePath("/panel/login")).then(() => {
          this.$toast.success(
            this.$t("logoutSuccessfully"),
            this.$t("successfully")
          );
        });
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>