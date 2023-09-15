<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form @submit.prevent="handleSubmit(login)">
        <h1 class="display-4 mb-10">{{ $t("panel.welcomeAgain") }}</h1>
        <p class="mb-30">{{ $t("panel.loginWithYourCredentials") }}</p>
        <div class="form-group mb-3">
          <ValidationProvider
            vid="email"
            :name="$t('panel.login.yourEmailAddress')"
            rules="required|min:2|email"
            v-slot="{ errors }"
            tag="div"
          >
            <label for="email" class="mb-5">{{
              $t("panel.login.yourEmailAddress")
            }}</label>
            <input
              id="email"
              class="form-control form-control-sm rounded-0"
              :placeholder="$t('panel.login.yourEmailAddress')"
              type="email"
              v-model="loginData.email"
              required
            />
            <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
          </ValidationProvider>
        </div>
        <div class="form-group mb-3">
          <ValidationProvider
            vid="password"
            :name="$t('panel.login.yourPassword')"
            rules="required|min:6"
            v-slot="{ errors }"
            tag="div"
          >
            <label for="password" class="mb-5">{{
              $t("panel.login.yourPassword")
            }}</label>
            <input
              id="password"
              class="form-control form-control-sm rounded-0"
              :placeholder="$t('panel.login.yourPassword')"
              type="password"
              v-model="loginData.password"
              required
            />
            <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
          </ValidationProvider>
        </div>
        <button
          class="btn btn-pink btn-block btn-sm rounded-0"
          type="submit"
          :disabled="invalid"
        >
          {{ $t("panel.login.login") }}
        </button>
        <button
          class="btn btn-default bg-white btn-block btn-sm rounded-0"
          @click.prevent="googleLogin"
        >
          <i class="fa fa-google"></i>
          {{ $t("panel.login.googleLogin") }}
        </button>
        <button
          class="btn btn-default bg-primary btn-block btn-sm rounded-0 text-white"
          @click.prevent="facebookLogin"
        >
          <i class="fa fa-facebook text-white"></i>
          {{ $t("panel.login.facebookLogin") }}
        </button>
        <button
          class="btn btn-default bg-info btn-block btn-sm rounded-0 text-white"
          @click.prevent="twitterLogin"
        >
          <i class="fa fa-twitter text-white"></i>
          {{ $t("panel.login.twitterLogin") }}
        </button>

        <p class="font-14 text-center mt-15">
          {{ $t("panel.login.havingProblemsLoggingIn") }}
        </p>
        <p class="text-center">
          <nuxt-link
            rel="dofollow"
            :title="$t('panel.login.forgotPassword')"
            :to="localePath('/panel/forgot-password')"
            >{{ $t("panel.login.forgotPassword") }}</nuxt-link
          >
        </p>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      loginData: {
        email: null,
        password: null,
        loginType: "email",
      },
    };
  },
  methods: {
    /**
     * Refresh Token Login
     */
    async login() {
      try {
        await this.$auth.loginWith("admin", {
          data: this.loginData,
        });
        this.$router.replace(this.localePath("/panel")).then(() => {
          this.$toast.success(
            this.$t("panel.welcome") +
              " <b>" +
              this.$auth.user.first_name +
              " " +
              this.$auth.user.last_name +
              "</b>",
            this.$t("successfully")
          );
        });
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
    /**
     * Google Login
     */
    async googleLogin() {
      try {
        const redirect_uri = this.$config.BASE_URL + "panel/login";
        window.location.href =
          "https://accounts.google.com/o/oauth2/v2/auth?client_id=" +
          this.$config.GOOGLE_CLIENT_ID +
          "&response_type=token id_token&scope=https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email&redirect_uri=" +
          redirect_uri +
          "&nonce=0394852-3190485-2490358&state=12345&prompt=consent";
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
    /**
     * Google Login Response
     */
    async makeGoogleLogin() {
      try {
        const params = new URLSearchParams(this.$route.hash.substr(1));
        const access_token = params.get("access_token");
        const id_token = params.get("id_token");
        const state = params.get("state");
        const token_type = params.get("token_type");
        const expires_in = params.get("expires_in");
        const scope = params.get("scope");
        const auth_user = params.get("authuser");
        const prompt = params.get("prompt");
        const version_info = params.get("version_info");
        const loginData = {
          state,
          access_token,
          token_type,
          expires_in,
          scope,
          id_token: id_token + " " + state,
          auth_user,
          prompt,
          version_info,
          loginType: "google",
        };
        await this.$auth.loginWith("admin", {
          data: loginData,
        });
        this.$router.replace(this.localePath("/panel")).then(() => {
          this.$toast.success(
            this.$t("panel.welcome") +
              " <b>" +
              this.$auth.user.first_name +
              " " +
              this.$auth.user.last_name +
              "</b>",
            this.$t("successfully")
          );
        });
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
    /**
     * Facebook Login
     */
    async facebookLogin() {
      try {
        const redirect_uri = this.$config.BASE_URL + "panel/login";
        let url =
          "https://www.facebook.com/v17.0/dialog/oauth?client_id=" +
          this.$config.FACEBOOK_CLIENT_ID +
          "&redirect_uri=" +
          redirect_uri +
          "&state=12345&response_type=token&scope=email,public_profile";
        window.location.href = url;
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
    /**
     * Facebook Login Response
     */
    async makeFacebookLogin() {
      try {
        let params = new URLSearchParams(this.$route.hash.substr(1));
        const access_token = params.get("access_token");
        const expires_in = params.get("expires_in");
        const loginData = {
          access_token,
          expires_in,
          loginType: "facebook",
        };
        await this.$auth.loginWith("admin", {
          data: loginData,
        });
        const returnData = await this.$axios.post(
          "panel/facebook-login",
          loginData
        );
        this.$router.replace(this.localePath("/panel")).then(() => {
          this.$toast.success(
            this.$t("panel.welcome") +
              " <b>" +
              this.$auth.user.first_name +
              " " +
              this.$auth.user.last_name +
              "</b>",
            this.$t("successfully")
          );
        });
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
    /**
     * Twitter Login
     */
    async twitterLogin() {
      try {
        const redirect_uri = this.$config.BASE_URL + "panel/login";
        const loginData = {
          redirect_uri,
        };
        const { data } = await this.$axios.get("panel/twitter-login", {
          params: loginData,
        });
        if (data.data.url) {
          localStorage.setItem("code_verifier", data.data.challenge);
          window.location.href = data.data.url;
        }
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
    /**
     * Twitter Login Response
     */
    async makeTwitterLogin() {
      try {
        const redirect_uri = this.$config.BASE_URL + "panel/login";
        const oauth_token = this.$route.query.oauth_token;
        const oauth_verifier = this.$route.query.oauth_verifier;
        const loginData = {
          redirect_uri,
          oauth_token,
          oauth_verifier,
          loginType: "twitter",
        };
        await this.$auth.loginWith("admin", {
          data: loginData,
        });
        this.$router.replace(this.localePath("/panel")).then(() => {
          this.$toast.success(
            this.$t("panel.welcome") +
              " <b>" +
              this.$auth.user.first_name +
              " " +
              this.$auth.user.last_name +
              "</b>",
            this.$t("successfully")
          );
        });
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
  },
  created() {
    /**
     * Run Google Login
     */
    if (this.$route.hash.startsWith("#state=")) {
      this.makeGoogleLogin();
    }
    /**
     * Run Facebook Login
     */
    if (this.$route.hash.startsWith("#access_token=")) {
      this.makeFacebookLogin();
    }
  },
  mounted() {
    /**
     * Run Twitter Login
     */
    if (this.$route.query.oauth_token && this.$route.query.oauth_verifier) {
      this.makeTwitterLogin();
    }
  },
};
</script>