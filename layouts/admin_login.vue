<template>
  <div>
    <!-- HK Wrapper -->
    <div class="hk-wrapper">
      <!-- Main Content -->
      <div class="hk-pg-wrapper hk-auth-wrapper">
        <BackendLoginHeader />
        <Nuxt />
      </div>
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
          href: "/backend/css/style.css",
        },
        { rel: 'icon', type: 'image/x-icon', href: this.$config.UPLOADS_URL+'/settings/'+this.$store.state.settings.favicon }
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
        { src: "/backend/js/init.js", defer: true, body: true },
      ],
    };
  },
  middleware: ["auth", "adminunauthenticated"],
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