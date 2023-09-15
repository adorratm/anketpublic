<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form @submit.prevent="handleSubmit(login)">
        <div class="pl-group-inp">
          <ValidationProvider
            vid="email"
            :name="$t('login.yourEmailAddress')"
            rules="required|min:2|email"
            v-slot="{ errors }"
            tag="div"
          >
            <b>{{ $t("login.yourEmailAddress") }}</b>
            <label for="email">
              <i class="fa fa-envelope-open"></i>
              <input
                id="email"
                type="email"
                v-model="loginData.email"
                :placeholder="$t('login.yourEmailAddress')"
                required
              />
            </label>
            <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
        </div>
        <div class="pl-group-inp mt-2">
          <ValidationProvider
            vid="password"
            :name="$t('login.yourPassword')"
            rules="required|min:6"
            v-slot="{ errors }"
            tag="div"
          >
            <b>{{ $t("login.yourPassword") }}</b>
            <label for="password">
              <i class="fa fa-lock"></i>
              <input
                id="password"
                :placeholder="$t('login.yourPassword')"
                type="password"
                v-model="loginData.password"
                required
              />
            </label>
            <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
        </div>
        <div class="d-flex flex-row">
          <div class="flex-fill">
            <input
              class="tgl tgl-flat"
              id="cb3"
              type="checkbox"
              name="rememberme"
              v-model="rememberme"
            />
            <label class="tgl-btn float-start me-2" for="cb3"></label>
            <label>{{ $t("login.rememberMe") }}</label>
          </div>
          <div class="text-right">
            <nuxt-link
              rel="dofollow"
              :title="$t('login.forgotPassword')"
              :to="localePath('/forgot-password')"
              >{{ $t("login.forgotPassword") }}</nuxt-link
            >
          </div>
        </div>
        <hr class="d-none" />
        <button class="nbtn green-btn" type="submit" :disabled="invalid">
          {{ $t("login.login") }}
        </button>
        <hr />

        <div class="orsign">{{ $t("login.orLoginWithSocialMedia") }}</div>
        <div class="pl-social-login">
          <a
            rel="dofollow"
            href="javascript:void(0)"
            class="pl-buttons bg-google"
            @click.prevent="googleLogin"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            :data-bs-title="$t('login.loginWithGoogle')"
          >
            <i class="fa fa-google"></i>
          </a>
          <a
            rel="dofollow"
            href="javascript:void(0)"
            class="pl-buttons bg-facebook"
            @click.prevent="facebookLogin"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            :data-bs-title="$t('login.loginWithFacebook')"
          >
            <i class="fa fa-facebook"></i>
          </a>
          <a
            rel="dofollow"
            href="javascript:void(0)"
            class="pl-buttons bg-twitter"
            @click.prevent="twitterLogin"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            :data-bs-title="$t('login.loginWithTwitter')"
          >
            <i class="fa fa-twitter"></i>
          </a>
        </div>
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
      rememberme: false,
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
        if (this.rememberme) {
          localStorage.remembermeEmail = this.loginData.email;
          localStorage.remembermePassword = this.loginData.password;
          localStorage.rememberme = true;
        } else {
          localStorage.removeItem("remembermeEmail");
          localStorage.removeItem("remembermePassword");
          localStorage.removeItem("rememberme");
        }
        await this.$auth.loginWith("user", {
          data: this.loginData,
        });

        this.$router.replace(this.localePath("/") + "?login=true").then(() => {
          this.$toast.success(
            this.$t("welcome") +
              " <b>" +
              this.$auth.user.first_name +
              " " +
              this.$auth.user.last_name +
              "</b>",
            this.$t("successfully")
          );
          $(".modal-backdrop").remove();
          $("body").removeClass("modal-open");
          document.body.style = "";
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
        const redirect_uri = this.$config.BASE_URL;
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
        await this.$auth.loginWith("user", {
          data: loginData,
        });
        this.$router.replace(this.localePath("/") + "?login=true").then(() => {
          this.$toast.success(
            this.$t("welcome") +
              " <b>" +
              this.$auth.user.first_name +
              " " +
              this.$auth.user.last_name +
              "</b>",
            this.$t("successfully")
          );
          $(".modal-backdrop").remove();
          $("body").removeClass("modal-open");
          document.body.style = "";
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
        const redirect_uri = this.$config.BASE_URL;
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
        await this.$auth.loginWith("user", {
          data: loginData,
        });
        await this.$axios.post("facebook-login", loginData);
        this.$router.replace(this.localePath("/") + "?login=true").then(() => {
          this.$toast.success(
            this.$t("welcome") +
              " <b>" +
              this.$auth.user.first_name +
              " " +
              this.$auth.user.last_name +
              "</b>",
            this.$t("successfully")
          );
          $(".modal-backdrop").remove();
          $("body").removeClass("modal-open");
          document.body.style = "";
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
        const redirect_uri = this.$config.BASE_URL;
        const loginData = {
          redirect_uri,
        };
        const { data } = await this.$axios.get("twitter-login", {
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
        const redirect_uri = this.$config.BASE_URL;
        const oauth_token = this.$route.query.oauth_token;
        const oauth_verifier = this.$route.query.oauth_verifier;
        const loginData = {
          redirect_uri,
          oauth_token,
          oauth_verifier,
          loginType: "twitter",
        };
        await this.$auth.loginWith("user", {
          data: loginData,
        });
        this.$router.replace(this.localePath("/") + "?login=true").then(() => {
          this.$toast.success(
            this.$t("welcome") +
              " <b>" +
              this.$auth.user.first_name +
              " " +
              this.$auth.user.last_name +
              "</b>",
            this.$t("successfully")
          );
          $(".modal-backdrop").remove();
          $("body").removeClass("modal-open");
          document.body.style = "";
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

    if (localStorage.remembermeEmail && localStorage.remembermePassword) {
      this.loginData.email = localStorage.remembermeEmail ?? null;
      this.loginData.password = localStorage.remembermePassword ?? null;
      this.rememberme = localStorage.rememberme ?? false;
    }
  },
};
</script>