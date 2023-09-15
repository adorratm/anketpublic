<template>
  <div class="col-4">
    <div class="pl-sidebar position-sticky">
      <div class="pl-widget">
        <h3 class="pl-title">
          {{ $t("featuredPolls") }}
        </h3>
        <div class="pl-content">
          <ul class="pl-polls" v-if="featuredPolls.length">
            <li v-for="featuredPoll in featuredPolls" :key="featuredPoll.id">
              <nuxt-link
                rel="dofollow"
                :to="localePath('/poll/' + featuredPoll.seo_url)"
                :title="featuredPoll.title"
              >
                <div class="pl-thumb">
                  <img
                    v-if="featuredPoll.images && featuredPoll.images[0]"
                    loading="lazy"
                    class="lazyload img-fluid rounded border"
                    :data-src="$config.UPLOADS_URL + '/polls/' + featuredPoll.images[0]"
                    :alt="featuredPoll.title"
                  />
                  <img
                    loading="lazy"
                    class="lazyload img-fluid"
                    :data-src="
                      $config.UPLOADS_URL + '/settings/' + $store.state.settings.logo
                    "
                    :alt="featuredPoll.title"
                    v-else
                  />
                </div>
              </nuxt-link>
              <div class="pl-body">
                <h4>
                  <nuxt-link
                    rel="dofollow"
                    :to="localePath('/poll/' + featuredPoll.seo_url)"
                    :title="featuredPoll.title"
                    >{{ featuredPoll.title }}</nuxt-link
                  >
                </h4>
                <div class="pl-details">
                  <i class="fa fa-user"></i
                  ><span> {{ featuredPoll.author_username }}</span>
                  <i class="fa fa-comments"></i
                  ><span>
                    {{ featuredPoll.comments_count }}
                    {{ $t("totalComments") }}</span
                  >
                </div>
              </div>
            </li>
          </ul>
          <div class="pl-not-found" v-else>{{ $t("poll.noPollsFound") }}</div>
        </div>
      </div>
      <!-- End Questions Widget -->
      <div class="pl-widget">
        <h3 class="pl-title">
          <i class="icon-organization icons"></i> {{ $t("popularCategories") }}
        </h3>
        <div class="pl-content">
          <ul class="categories" v-if="popularCategories.length">
            <li v-for="category in popularCategories" :key="category.id">
              <nuxt-link
                rel="dofollow"
                :to="localePath('/categories/' + category.seo_url)"
                :title="category.title"
                class="d-flex align-items-center align-self-center align-content-center"
              >
                <span
                  ><img
                    width="75"
                    height="75"
                    v-if="category.icon"
                    loading="lazy"
                    class="lazyload img-fluid"
                    :data-src="$config.UPLOADS_URL + '/categories/' + category.icon"
                    :alt="category.title"
                  />
                  <i class="fa fa-folder-open fa-2x" v-else></i>
                </span>
                <small class="fs-5 ms-2 text-secondary">{{ category.title }}</small>
              </nuxt-link>
            </li>
          </ul>
          <div class="pl-not-found" v-else>{{ $t("notFound") }}</div>
        </div>
      </div>
      <!-- End Categories Widget -->
      <div class="pl-widget">
        <h3 class="pl-title">
          <i class="icon-social-facebook icons"></i> {{ $t("socialMedia") }}
        </h3>
        <div class="pl-content">
          <div class="pl-sociallink">
            <a
              rel="dofollow"
              v-if="settings.facebook"
              title="Facebook"
              :href="settings.facebook"
              target="_blank"
              class="pl-buttons bg-facebook"
              ><i class="fab fa-facebook"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.instagram"
              title="Instagram"
              :href="settings.instagram"
              target="_blank"
              class="pl-buttons bg-instagram"
              ><i class="fab fa-instagram"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.linkedin"
              title="Linkedin"
              :href="settings.linkedin"
              target="_blank"
              class="pl-buttons bg-linkedin"
              ><i class="fab fa-linkedin"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.twitter"
              title="Twitter"
              :href="settings.twitter"
              target="_blank"
              class="pl-buttons bg-twitter"
              ><i class="fab fa-twitter"></i
            ></a>
            <a
              rel="dofollow"
              title="Whatsapp"
              v-if="address_informations[0]?.phones[0]?.whatsapp"
              :href="
                'https://wa.me/' +
                address_informations[0]?.phones[0]?.whatsapp?.replaceAll(' ', '') +
                '/?text=' +
                settings.company_name
              "
              target="_blank"
              class="pl-buttons bg-2"
              ><i class="fab fa-whatsapp"></i
            ></a>
            <a
              rel="dofollow"
              title="Youtube"
              v-if="settings.youtube"
              :href="settings.youtube"
              target="_blank"
              class="pl-buttons bg-youtube"
              ><i class="fab fa-youtube"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.email"
              :title="$t('email')"
              :href="'mailto:' + settings.email + '?Subject=' + settings.company_name"
              target="_blank"
              class="pl-buttons bg-4"
              ><i class="far fa-envelope"></i
            ></a>
          </div>
        </div>
      </div>
      <!-- End Social Box Widget -->

      <div class="pl-widget">
        <h3 class="pl-title"><i class="fa fa-trophy"></i> {{ $t("featuredUsers") }}</h3>
        <div class="pl-content">
          <ul class="pl-users crd" v-if="featuredUsers.length">
            <li
              v-for="featuredUser in featuredUsers"
              :key="featuredUser.id"
              class="d-flex align-items-center align-self-center align-content-center"
            >
              <nuxt-link
                rel="dofollow"
                :to="localePath('/profile/' + featuredUser.username)"
                :title="featuredUser.username"
              >
                <img
                  v-if="featuredUser.picture"
                  loading="lazy"
                  class="lazyload img-fluid"
                  :data-src="$config.UPLOADS_URL + '/users/' + featuredUser.picture"
                  :alt="featuredUser.username"
                />
                <i class="fa fa-2x fa-user-circle" v-else></i>
              </nuxt-link>
              <nuxt-link
                rel="dofollow"
                :to="localePath('/profile/' + featuredUser.username)"
                :title="featuredUser.username"
              >
                <h3 class="ms-3">
                  {{ featuredUser.username }}
                  <small class="d-block mt-2" v-if="featuredUser.company_name">
                    <i class="fa fa-building"></i>
                    <b>{{ featuredUser.company_name }} </b>
                  </small>
                </h3>
              </nuxt-link>
            </li>
          </ul>
          <div class="pl-not-found" v-else>
            {{ $t("userNotFound") }}
          </div>
        </div>
      </div>
      <!-- End Follow Widget -->

      <div class="pl-widget">
        <h3 class="pl-title"><i class="icon-star icons"></i> {{ $t("followUsers") }}</h3>
        <div class="pl-content">
          <ssr-carousel
            :autoplay-delay="5"
            loop
            class="pl-users list-unstyled"
            center
            :slides-per-page="1"
            v-if="followUsers.length"
          >
            <div class="slide" v-for="followUser in followUsers" :key="followUser.id">
              <div class="list-unstyled">
                <img
                  loading="lazy"
                  class="lazyload img-fluid"
                  v-if="followUser.picture"
                  :data-src="$config.UPLOADS_URL + '/users/' + followUser.picture"
                  :alt="followUser.username"
                />
                <i class="fa fa-3x fa-user-circle" v-else></i>
                <h3>
                  <nuxt-link
                    rel="dofollow"
                    :to="localePath('/profile/' + followUser.username)"
                    :title="followUser.username"
                    >{{ followUser.username }}</nuxt-link
                  >
                  <p v-if="followUser.company_name">
                    {{ followUser.company_name }}
                  </p>
                </h3>
                <div class="pl-details">
                  <span
                    ><i class="fas fa-users"></i><b>{{ followUser.follower_count }}</b
                    ><br />
                    {{ $t("follower") }}</span
                  >
                  <span
                    ><i class="fas fa-user-plus"></i
                    ><b>{{ followUser.following_count }}</b
                    ><br />
                    {{ $t("following") }}</span
                  >
                  <span
                    ><i class="fas fa-tasks"></i><b>{{ followUser.poll_count }}</b
                    ><br />
                    {{ $t("totalPolls") }}</span
                  >
                  <span
                    ><i class="fas fa-comments"></i><b>{{ followUser.comment_count }}</b
                    ><br />
                    {{ $t("totalComments") }}</span
                  >
                </div>

                <a
                  rel="dofollow"
                  :title="$t('follow')"
                  href="javascript:void(0)"
                  class="pl-user-follow-list nbtn green-btn pl-sidebar-fl"
                  @click.prevent="followUserFnc(followUser.id)"
                >
                  <i class="fa fa-user-plus"></i>
                  {{ $t("follow") }}
                </a>
              </div>
            </div>
          </ssr-carousel>
          <div class="pl-not-found list-unstyled" v-else>
            {{ $t("userNotFound") }}
          </div>
        </div>
      </div>
      <!-- End Follow Widget -->
    </div>
    <!-- End Sidebar -->
  </div>
</template>

<script>
export default {
  name: "Sidebar",
  data() {
    return {
      settings: {},
      address_informations: [],
      featuredUsers: [],
      featuredPolls: [],
      popularCategories: [],
      followUsers: [],
    };
  },
  mounted() {
    this.settings = this.$store.state.settings ? this.$store.state.settings : {};
    this.address_informations = this.$store.state.settings.address_informations ? JSON.parse(
      this.$store.state.settings.address_informations
    ) : [];
    this.getFeaturedPolls();
    this.getFeaturedUsers();
    this.getPopularCategories();
    this.getFollowUsers();
  },
  methods: {
    async followUserFnc(following_id) {
      try {
        const { data } = await this.$axios.post("follow-user", {
          following_id,
          user_id: this.$auth.user.id,
        });

        if (data.status) {
          this.$toast.success(data.message, data.title);
          this.getFollowUsers();
          this.getFeaturedUsers();
        } else {
          this.$toast.error(data.message, data.title);
          this.getFollowUsers();
          this.getFeaturedUsers();
        }
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
          this.getFollowUsers();
          this.getFeaturedUsers();
        }
        console.log(error);
      }
    },
    async getFeaturedPolls() {
      try {
        const { data } = await this.$axios.$get("featured-polls");
        this.featuredPolls = data;
        if (this.featuredPolls.length) {
          this.featuredPolls.map((value) => {
            value.images = value.images ? JSON.parse(value.images) : null;
          });
        }
      } catch (error) {
        this.featuredPolls = [];
        console.log(error);
      }
    },
    async getFeaturedUsers() {
      try {
        const { data } = await this.$axios.$get("featured-users");
        this.featuredUsers = data;
      } catch (error) {
        console.log(error);
        this.featuredUsers = [];
      }
    },
    async getPopularCategories() {
      try {
        const { data } = await this.$axios.$get("popular-categories");
        this.popularCategories = data;
      } catch (error) {
        this.popularCategories = [];
        console.log(error);
      }
    },
    async getFollowUsers() {
      try {
        const { data } = await this.$axios.$get("follow-users");
        this.followUsers = data;
      } catch (error) {
        this.followUsers = [];
        console.log(error);
      }
    },
  },
};
</script>
