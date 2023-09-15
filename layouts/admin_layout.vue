<template>
  <div>
    <!-- HK Wrapper -->
    <div class="hk-wrapper hk-vertical-nav">
      <!-- Top Navbar -->
      <BackendHeader />
      <!-- /Top Navbar -->

      <!-- Vertical Nav -->
      <BackendSidebar />
      <!-- /Vertical Nav -->

      <!-- Main Content -->
      <div class="hk-pg-wrapper">
        <!-- Container -->
        <div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
          <Nuxt />
        </div>
        <!-- /Container -->

        <!-- Footer -->
        <BackendFooter />
        <!-- /Footer -->

        <!-- /Main Content -->
      </div>
      <!-- /HK Wrapper -->
    </div>
  </div>
</template>


<script>
export default {
  head() {
    return {
      titleTemplate:
        this.$store.state.settings.company_name +
        " - Adorratm Anket Yönetim Paneli",
      meta: [
        {
          name: "description",
          content: this.$store.state.settings.meta_description,
        },
        {
          name: "author",
          content: "Emre KILIÇ https://github.com/adorratm",
        },
      ],
      htmlAttrs: {
        lang: "tr",
      },
      link: [
        {
          rel: "stylesheet",
          href: "/global/plugins/bootstrap/css/bootstrap.min.css",
        },
        {
          rel: "stylesheet",
          href: "/backend/css/animate.css",
        },
        {
          rel: "stylesheet",
          href: "/global/css/all.min.css",
        },
        {
          rel: "stylesheet",
          href: "/global/css/v4-shims.min.css",
        },
        {
          rel: "stylesheet",
          href: "https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.3.1/skins/ui/oxide/skin.min.css",
        },
        {
          rel: "stylesheet",
          href: "/backend/css/style.css",
        },
        {
          rel: "icon",
          type: "image/x-icon",
          href:
            this.$config.UPLOADS_URL +
            "/settings/" +
            this.$store.state.settings.favicon,
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
          src: "https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.0/tinymce.min.js",
          defer: true,
          body: true,
        },
        { src: "/backend/js/init.js?v=" + Date(), defer: true, body: true },
      ],
    };
  },
  middleware: ["auth", "adminauthenticated"],
  mounted() {
    console.log(this.$auth.user.role_id)
  },
};

</script>

<style scoped>
.layout-enter-active,
.layout-leave-active {
  transition: opacity 0.5s;
}
.layout-enter,
.layout-leave-to {
  opacity: 0;
}
</style>