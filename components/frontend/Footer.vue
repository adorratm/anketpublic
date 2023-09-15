<template>
  <div>
    <div class="pr-newindexcount">
      <div class="container">
        <ul class="mx-auto d-flex justify-content-center">
          <li>
            <i class="fas fa-tasks"></i><b>{{ $store.state.counters.polls }} </b>
            {{ $t("totalPolls") }}
          </li>
          <li>
            <i class="fa fa-thumbs-up"></i><b>{{ $store.state.counters.votes }} </b>
            {{ $t("totalVotes") }}
          </li>
          <li>
            <i class="fa fa-comments"></i><b>{{ $store.state.counters.comments }} </b>
            {{ $t("totalComments") }}
          </li>
          <li>
            <i class="fa fa-folder-open"></i
            ><b>
              {{ $store.state.counters.categories }}
            </b>
            {{ $t("totalCategories") }}
          </li>
        </ul>
      </div>
    </div>

    <div class="pl-footer">
      <div class="container">
        <div class="pl-statistics">
          <div class="pt-logo">
            <nuxt-link rel="dofollow" :title="settings.company_name" :to="localePath('/')"
              ><img
                loading="lazy"
                class="lazyload img-fluid"
                :data-src="$config.UPLOADS_URL + '/settings/' + settings.logo"
                :alt="settings.company_name"
            /></nuxt-link>
          </div>
          <p>{{ settings.meta_description }}</p>
          <div class="pl-sociallink">
            <a
              rel="dofollow"
              v-if="settings.facebook"
              :href="settings.facebook"
              target="_blank"
              class="bg-facebook"
              title="Facebook"
              ><i class="fab fa-facebook"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.instagram"
              :href="settings.instagram"
              target="_blank"
              class="bg-instagram"
              title="Instagram"
              ><i class="fab fa-instagram"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.twitter"
              :href="settings.twitter"
              target="_blank"
              class="bg-twitter"
              title="Twitter"
              ><i class="fab fa-twitter"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.linkedin"
              :href="settings.linkedin"
              target="_blank"
              class="bg-linkedin"
              title="Linkedin"
              ><i class="fab fa-linkedin"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.medium"
              :href="settings.medium"
              target="_blank"
              class="bg-medium"
              title="Medium"
              ><i class="fab fa-medium"></i
            ></a>
            <a
              rel="dofollow"
              v-if="address_informations[0]?.phones[0]?.whatsapp"
              :href="
                'https://wa.me/' +
                address_informations[0]?.phones[0]?.whatsapp?.replaceAll(' ', '') +
                '/?text=' +
                settings.company_name
              "
              target="_blank"
              class="bg-2"
              title="Whatsapp"
              ><i class="fab fa-whatsapp"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.youtube"
              :href="settings.youtube"
              target="_blank"
              class="bg-youtube"
              title="Youtube"
              ><i class="fab fa-youtube"></i
            ></a>
            <a
              rel="dofollow"
              v-if="settings.email"
              :href="'mailto:' + settings.email + '?Subject=' + settings.company_name"
              target="_blank"
              class="bg-4"
              title="Email"
              ><i class="far fa-envelope"></i
            ></a>
          </div>
        </div>
        <!-- End Statistics -->

        <div class="pl-links">
          <h3>{{ $t("footerMenus") }}</h3>
          <ul v-if="$store.state.footerMenus">
            <li v-for="menu in $store.state.footerMenus" :key="menu.id">
              <nuxt-link
                rel="dofollow"
                :to="menu.url"
                :title="menu.title"
                :target="menu.target"
                >{{ menu.title }}</nuxt-link
              >
            </li>
          </ul>
        </div>
        <!-- End Links -->
        <div class="pl-subsecribe">
          <h3>{{ $t("subscribeOurEmailNewsletter") }}:</h3>
          <div class="pl-subs">
            <form id="subscribe" v-on:submit.prevent="handleSubmit">
              <p>{{ $t("subscribeNewsletter") }}</p>
              <div class="pl-group-inp">
                <input type="text" name="email" :placeholder="$t('newsletterEmail')" />
                <i class="fas fa-at"></i>
              </div>
              <button type="submit" class="nbtn green-btn">
                <span><i class="fas fa-follow"></i> {{ $t("subscribe") }}</span>
              </button>
            </form>
          </div>
        </div>
        <!-- End Subsecribe -->
      </div>
      <!-- End Container -->
    </div>
    <!-- End Footer -->

    <div class="pl-copyright">
      <div class="container">
        <div class="row align-items-center align-self-center align-content-center">
          <div class="col-sm-10">
            Copyright &copy; {{ new Date().getFullYear() }}
            <nuxt-link
              class="text-dark"
              rel="dofollow"
              :title="settings.company_name"
              :to="localePath('/')"
              >{{ settings.company_name }}</nuxt-link
            >. {{ $t("allRightsReserved") }}
          </div>
          <div class="col-sm-2">
            <a
              href="https://github.com/adorratm"
              target="_blank"
              title="Emre KILIÇ"
              rel="dofollow"
            >
              <img
                width="50"
                alt="Emre KILIÇ"
                style="filter: drop-shadow(1px 1px 1px black)"
                loading="lazy"
                class="lazyload img-fluid rounded-circle"
                data-src="https://avatars.githubusercontent.com/u/39022587"
              />
            </a>
          </div>
        </div>
      </div>
      <!-- End Container -->
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      settings: {},
      address_informations: [],
    };
  },
  mounted() {
    this.settings = this.$store.state.settings;
    this.address_informations = this.$store.state.settings.address_informations ? JSON.parse(
      this.$store.state.settings.address_informations
    ) : [];
  },
};
</script>
