export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    htmlAttrs: {
      lang: 'tr'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
  },

  // Server configuration: https://nuxtjs.org/docs/2.x/configuration-glossary/configuration-server
  server: {
    port: process.env.PORT || 3000, // default: 3000
    host: '0.0.0.0', // default: localhost
    timing: false // default: false
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    { src: '~/plugins/axios' },
    { src: '~/plugins/vee-validate' },
    { src: '~/plugins/vue-good-table', ssr: false },
    { src: '~/plugins/vue-izitoast', ssr: false },
    { src: '~/plugins/v-viewer', ssr: false },
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    'nuxt-lazysizes',
    'vue-ssr-carousel/nuxt'
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/i18n',
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    'vue-sweetalert2/nuxt',
  ],

  i18n: {
    locales: [{ code: 'tr', file: 'tr.js', iso: 'tr-TR', name: 'Türkçe' }],
    langDir: 'locales',
    defaultLocale: 'tr',
    lazy: true,
    strategy: 'no_prefix',
  },

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    baseURL: process.env.API_URL,
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    // Add exception
    transpile: [
      "vee-validate/dist/rules"
    ],
    /*
      ** You can extend webpack config here
      */
    extend(config, ctx) {
      // ...
    }
  },

  loading: '~/components/backend/LoadingBar.vue',

  publicRuntimeConfig: {
    API_URL: process.env.API_URL,
    UPLOADS_URL: process.env.UPLOADS_URL,
    BASE_URL: process.env.BASE_URL,
    FACEBOOK_CLIENT_ID: process.env.FACEBOOK_CLIENT_ID,
    FACEBOOK_CLIENT_SECRET: process.env.FACEBOOK_CLIENT_SECRET,
    GOOGLE_CLIENT_ID: process.env.GOOGLE_CLIENT_ID,
  },

  auth: {
    redirect: false,
    strategies: {
      admin: {
        scheme: "refresh",
        token: {
          property: "data.token",
          global: true,
          required: true,
          type: "Bearer",
          maxAge: 7200 // 2 hours
        },
        user: {
          url: "/panel/me", method: "get",
          property: "data",
          autoFetch: true
        },
        refreshToken: {
          property: "data.token",
          data: "data",
          maxAge: 30 * 24 * 60 * 60 // 30 days
        },
        endpoints: {
          login: {
            url: "/panel/login", method: "post",
            propertyName: "data.token"
          },
          refresh: {
            url: "/panel/refresh-token", method: "post"
          },
          logout: false,
          user: {
            url: "/panel/me", method: "get",
            propertyName: "data"
          }
        },
        autoLogout: false,
      },
      user: {
        scheme: "refresh",
        token: {
          property: "data.token",
          global: true,
          required: true,
          type: "Bearer",
          maxAge: 7200 // 2 hours
        },
        user: {
          url: "/me", method: "get",
          property: "data",
          autoFetch: true
        },
        refreshToken: {
          property: "data.token",
          data: "data",
          maxAge: 30 * 24 * 60 * 60 // 30 days
        },
        endpoints: {
          login: {
            url: "/login", method: "post",
            propertyName: "data.token"
          },
          refresh: {
            url: "/refresh-token", method: "post"
          },
          logout: false,
          user: {
            url: "/me", method: "get",
            propertyName: "data"
          }
        },
        autoLogout: false,
      },
    },
  },
}
