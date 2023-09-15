<template>
  <div class="col-lg-8">
    <div class="pl-main" v-if="user">
      <div class="pl-box">
        <div class="pl-cover">
          <img
            loading="lazy"
            class="lazyload img-fluid"
            :data-src="user.cover ? $config.UPLOADS_URL + '/users/' + user.cover : '/frontend/images/cover.webp'"
            :alt="user?.username"
          />
          <div class="pl-content">
            <div class="d-flex flex-row">
              <div class="pe-3">
                <div class="pl-thumb">
                  <img
                    v-if="user.picture"
                    loading="lazy"
                    class="lazyload img-fluid"
                    :data-src="$config.UPLOADS_URL + '/users/' + user.picture"
                    :alt="user.username"
                  />
                  <i class="fa fa-user-circle fa-3x" v-else></i>
                </div>
              </div>
              <div class="flex-grow-1">
                <div class="pl-dtable">
                  <div class="pl-vmiddle">
                    <h3 class="pl-name fw-semibold">
                      {{ user.first_name + " " + user.last_name }}
                    </h3>
                    <div class="pl-username">
                      <nuxt-link
                        rel="dofollow"
                        :title="user.username"
                        class="text-white fw-bold"
                        :to="localePath('/profile/')"
                        >@{{ user.username }}</nuxt-link
                      >
                    </div>
                  </div>
                </div>
                <div class="pl-options">
                  <div
                    class="pl-social"
                    v-if="
                      user.facebook ||
                      user.instagram ||
                      user.twitter ||
                      user.linkedin ||
                      user.youtube ||
                      user.medium ||
                      user.pinterest ||
                      user.tiktok ||
                      user.reddit
                    "
                  >
                    <a
                      rel="dofollow"
                      title="Twitter"
                      v-if="user.twitter"
                      :href="user.twitter"
                      target="_blank"
                      class="bg-twitter"
                      ><i class="fa fa-twitter"></i
                    ></a>

                    <a
                      rel="dofollow"
                      title="Facebook"
                      v-if="user.facebook"
                      :href="user.facebook"
                      target="_blank"
                      class="bg-facebook"
                      ><i class="fa fa-facebook"></i
                    ></a>

                    <a
                      rel="dofollow"
                      title="Instagram"
                      v-if="user.instagram"
                      :href="user.instagram"
                      target="_blank"
                      class="bg-instagram"
                      ><i class="fa fa-instagram"></i
                    ></a>

                    <a
                      rel="dofollow"
                      title="Youtube"
                      v-if="user.youtube"
                      :href="user.youtube"
                      target="_blank"
                      class="bg-google"
                      ><i class="fa fa-youtube"></i
                    ></a>
                    <a
                      rel="dofollow"
                      title="Linkedin"
                      v-if="user.linkedin"
                      :href="user.linkedin"
                      target="_blank"
                      class="bg-youtube"
                      ><i class="fa fa-linkedin"></i
                    ></a>
                  </div>
                </div>
              </div>
            </div>

            <i
              v-if="user.type === 'CORPORATE'"
              class="fa fa-check-circle pl-verified"
              data-bs-toggle="tooltip"
              data-bs-placement="top"
              :data-bs-title="$t('corporateAccount')"
            ></i>
          </div>
        </div>
        <div class="pl-counts" v-if="statistics">
          <div class="pl-count">
            <nuxt-link
              rel="dofollow"
              class="text-dark"
              :to="localePath('/profile/user-followers/' + user.username)"
              :title="$t('profile.myFollowers')"
            >
              <b>
                <i class="fa fa-users"></i>
                <span class="pl-show-followers">{{
                  statistics.followers
                }}</span>
              </b>
              {{ $t("follower") }}
            </nuxt-link>
          </div>
          <div class="pl-count">
            <nuxt-link
              rel="dofollow"
              class="text-dark"
              :to="localePath('/profile/user-followings/' + user.username)"
              :title="$t('profile.myFollowings')"
            >
              <b><i class="fa fa-user-plus"></i>{{ statistics.followings }}</b>
              {{ $t("following") }}
            </nuxt-link>
          </div>
          <div class="pl-count">
            <b><i class="fa fa-check"></i>{{ statistics.votes }}</b>
            {{ $t("totalVotes") }}
          </div>
          <div class="pl-count">
            <nuxt-link
              rel="dofollow"
              class="text-dark"
              :to="localePath('/profile/user-polls/' + user.username)"
              :title="$t('profile.myPolls')"
            >
              <b><i class="fa fa-question-circle"></i>{{ statistics.polls }}</b>
              {{ $t("totalPolls") }}
            </nuxt-link>
          </div>
          <div class="pl-count">
            <nuxt-link
              rel="dofollow"
              class="text-dark"
              :to="localePath('/profile/user-comments/' + user.username)"
              :title="$t('profile.myComments')"
            >
              <b><i class="fa fa-comment"></i>{{ statistics.comments }}</b>
              {{ $t("totalComments") }}
            </nuxt-link>
          </div>
        </div>
      </div>
      <FrontendPollsList
        :isprofile="true"
        :data_url="'user-polls/' + user.username"
        :isfrontend="false"
      />
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
      statistics: null,
    };
  },
  mounted() {
    this.getStatistics();
    if (this.$auth?.user?.username === this.$route.params.id) {
      this.$router.replace(this.localePath("/profile"));
    } else {
      this.getUser();
    }
    this.$nextTick(() => {
      this.$nuxt.$loading.start();
      setTimeout(() => {
        $(".preloader-it").delay(500).fadeOut("slow");
        this.$nuxt.$loading.finish();
      }, 500);
    });
  },
  methods: {
    async getUser() {
      try {
        const { data } = await this.$axios.get(
          "/profile/" + this.$route.params.id
        );

        this.user = data.data;
        this.$store.commit("SET_SLIDER", false);
        this.$store.commit("SET_BREADCRUMB", {
          showBreadCrumb: true,
          breadCrumbTitle: this.user.first_name + " " + this.user.last_name,
          breadCrumbDescription: this.$t("profile.profile"),
        });
      } catch (error) {
        console.log(error);
        if (error.response.data.message) {
          this.$toast.error(
            error.response.data.message,
            this.$t("unsuccessfully")
          );
          this.$router.replace(this.localePath("/profile"));
        }
      }
    },
    async getStatistics() {
      try {
        const { data } = await this.$axios.get(
          "/statistics/" + this.$route.params.id
        );
        this.statistics = data.data;
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
      title: this.$t("profile.profile"),
    };
  },
};
</script>