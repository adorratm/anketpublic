<template>
  <div>
    <FrontendHeader />
    <FrontendSlider v-if="showSlider" />
    <FrontendBreadcrumb
      v-if="showBreadCrumb"
      :title="breadCrumbTitle"
      :description="breadCrumbDescription"
    />
    <div class="pl-page pl-newpageprofile">
      <div class="container">
        <div class="row h-100 position-relative">
          <Nuxt />
          <FrontendSidebar />
        </div>
      </div>
    </div>
    <FrontendFooter />
    <FrontendLoginModal v-if="!$auth.loggedIn" />
    <div id="pagetop" class="position-fixed" v-show="scY > 300" @click.prevent="toTop">
      <i class="fas fa-arrow-up"></i>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  head() {
    return {
      titleTemplate: "%s - " + this.$store.state.settings.company_name,
      meta: [
        {
          name: "description",
          content: this.$store.state.settings.meta_description,
        },
        {
          name: "author",
          content: "Emre KILIÇ https://github.com/adorratm",
        },
        // GOOGLE
        {
          itemprop: "name",
          content: this.$store.state.settings.company_name,
        },
        {
          itemprop: "description",
          content: this.$store.state.settings.meta_description,
        },
        {
          itemprop: "image",
          content:
            this.$config.UPLOADS_URL + "/settings/" + this.$store.state.settings.logo,
        },
        // TWITTER
        {
          name: "twitter:card",
          content: "product",
        },
        {
          name: "twitter:site",
          content: "@adorratm",
        },
        {
          name: "twitter:title",
          content: this.$store.state.settings.company_name,
        },
        {
          name: "twitter:description",
          content: this.$store.state.settings.meta_description,
        },
        {
          name: "twitter:creator",
          content: "@adorratm",
        },
        {
          name: "twitter:image",
          content:
            this.$config.UPLOADS_URL + "/settings/" + this.$store.state.settings.logo,
        },
        // Open Graph
        {
          property: "og:title",
          content: this.$store.state.settings.company_name,
        },
        {
          property: "og:type",
          content: "polls",
        },
        {
          property: "og:url",
          content: this.$config.BASE_URL,
        },
        {
          property: "og:image",
          content:
            this.$config.UPLOADS_URL + "/settings/" + this.$store.state.settings.logo,
        },
        {
          property: "og:description",
          content: this.$store.state.settings.meta_description,
        },
        {
          property: "og:site_name",
          content: this.$store.state.settings.company_name,
        },
        {
          property: "og:locale",
          content: "tr_TR",
        },
      ],
      link: [
        {
          rel: "stylesheet",
          href:
            "https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,900%7CGentium+Basic:400italic&display=swap",
          as: "style",
        },
        {
          rel: "stylesheet",
          href:
            "https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,300,700&display=swap",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/global/plugins/bootstrap/css/bootstrap.min.css",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/global/css/all.min.css",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/global/css/v4-shims.min.css",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/frontend/css/assets/owl.carousel.min.css",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/frontend/css/popupConsent.css",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/frontend/css/assets/animate.css",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/frontend/css/scroll.css",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/frontend/css/css.css",
          as: "style",
        },
        {
          rel: "stylesheet",
          href: "/frontend/css/new.css",
          as: "style",
        },
        {
          rel: "icon",
          type: "image/x-icon",
          href:
            this.$config.UPLOADS_URL + "/settings/" + this.$store.state.settings.favicon,
        },
      ],
      script: [
        {
          src: "/global/plugins/jquery/jquery.min.js",
          defer: true,
          body: true,
        },
        {
          src: "/global/plugins/jquery-migrate/jquery-migrate.min.js",
          defer: true,
          body: true,
        },
        {
          src: "/global/plugins/bootstrap/js/bootstrap.bundle.min.js",
          defer: true,
          body: true,
        },
        {
          src: "/frontend/js/assets/jquery.livequery.js",
          defer: true,
          body: true,
        },
        {
          src: "/frontend/js/search.js",
          defer: true,
          body: true,
        },
        {
          src: "/frontend/js/assets/jquery.jscroll.min.js",
          defer: true,
          body: true,
        },
        {
          src: "/frontend/js/jquery.scrollbar.js",
          defer: true,
          body: true,
        },
        {
          src: "/frontend/js/popupConsent.min.js",
          defer: true,
          body: true,
        },
        {
          src: "/frontend/js/Chart.min.js",
          defer: true,
          body: true,
        },
        {
          src: "/frontend/js/html2pdf.bundle.min.js",
          defer: true,
          body: true,
        },
        {
          src: "/frontend/js/masonry.pkgd.min.js",
          defer: true,
          body: true,
        },
        { src: "/frontend/js/custom.js", defer: true, body: true },
      ],
      htmlAttrs: {
        lang: "tr",
      },
    };
  },
  computed: {
    ...mapState([
      "showBreadCrumb",
      "breadCrumbTitle",
      "breadCrumbDescription",
      "showSlider",
    ]),
  },
  data() {
    return {
      scTimer: 0,
      scY: 0,
    };
  },
  methods: {
    handleScroll: function () {
      if (this.scTimer) return;
      this.scTimer = setTimeout(() => {
        this.scY = window.scrollY;
        clearTimeout(this.scTimer);
        this.scTimer = 0;
      }, 100);
    },
    toTop: function () {
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    },
  },
  mounted() {
    window.addEventListener("scroll", this.handleScroll);
    this.$nextTick(() => {
      setTimeout(() => {
        if (document.cookie.split(/; */).indexOf("popupConsent=true") == "-1") {
          popupConsent({
            textPopup: this.$t("popupContent")
              .replace(
                "{link_privacy}",
                '<a rel="dofollow" title="' +
                  this.$t("privacyPolicy") +
                  '" href="' +
                  this.localePath("/pages/gizlilik-sozlesmesi") +
                  '" target="_blank">' +
                  this.$t("privacyPolicy") +
                  "</a>"
              )
              .replace(
                "{link_terms}",
                '<a rel="dofollow" title="' +
                  this.$t("termsPolicy") +
                  '" href="' +
                  this.localePath("/pages/kullanim-kosullari") +
                  '" target="_blank">' +
                  this.$t("termsPolicy") +
                  "</a>"
              )
              .replace(
                "{link_cookie}",
                '<a rel="dofollow" title="' +
                  this.$t("cookiePolicy") +
                  '" href="' +
                  this.localePath("/pages/cerez-politikasi") +
                  '" target="_blank">' +
                  this.$t("cookiePolicy") +
                  "</a>"
              ),
            textButtonAccept: this.$t("accept"),
            textButtonConfigure: this.$t("configure"),
            textButtonSave: this.$t("accept"),

            authorization: [
              {
                textAuthorization: this.$t("privacyPolicy"),
                nameCookieAuthorization: "autoriseGeolocation",
              },
              {
                textAuthorization: this.$t("termsPolicy"),
                nameCookieAuthorization: "targetedAdvertising",
              },
              {
                textAuthorization: this.$t("cookiePolicy"),
                nameCookieAuthorization: "cookieConsent",
              },
            ],
          });
        }
        const tooltipTriggerList = document.querySelectorAll(
          '[data-bs-toggle="tooltip"]'
        );
        const tooltipList = [...tooltipTriggerList].map(
          (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
        );
      }, 1000);
      if (this.$route.query.login === "false") {
        setTimeout(() => {
          $("#sign-modal").modal("show");
        }, 500);
      }
    });
  },
};
</script>
