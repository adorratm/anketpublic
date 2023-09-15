<template>
  <div class="pl-signup" id="pl-signup">
    <h4 class="pl-page-head">
      {{ $t("register.register") }}
      <i class="fas fa-key"></i>
    </h4>
    <div class="pl-social-login">
      <p class="alert alert-danger mb-3" role="alert">
        {{ $t("register.ifYouLoggedinWithSocialMedia") }}
      </p>
      <a
        rel="dofollow"
        href="javascript:void(0)"
        class="pl-buttons bg-google"
        @click.prevent="googleLogin"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        :data-bs-title="$t('login.loginWithGoogle')"
      >
        <i class="fa fa-google"></i> {{ $t("login.loginWithGoogle") }}
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
        <i class="fa fa-facebook"></i> {{ $t("login.loginWithFacebook") }}
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
        <i class="fa fa-twitter"></i> {{ $t("login.loginWithTwitter") }}
      </a>
      <p class="alert alert-info" role="alert">
        {{ $t("register.termsAndPolicy") }}
      </p>
    </div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form class="pl-form" @submit.prevent="handleSubmit(register)">
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp">
              <ValidationProvider
                vid="first_name"
                :name="$t('register.firstName')"
                rules="required|min:2|max:70"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.firstName") }}</b> <b class="red">*</b>
                <label for="first_name">
                  <i class="fa fa-user"></i>
                  <input
                    id="first_name"
                    type="text"
                    v-model="registerData.first_name"
                    :placeholder="$t('register.firstName')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp">
              <ValidationProvider
                vid="last_name"
                :name="$t('register.lastName')"
                rules="required|min:2|max:70"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.lastName") }}</b> <b class="red">*</b>
                <label for="last_name">
                  <i class="fa fa-user"></i>
                  <input
                    id="last_name"
                    type="text"
                    v-model="registerData.last_name"
                    :placeholder="$t('register.lastName')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp">
              <ValidationProvider
                vid="gender"
                rules="required"
                :name="$t('register.gender')"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.gender") }}</b> <b class="red">*</b>
                <label for="gender">
                  <i class="fa fa-venus-mars"></i>
                  <v-select
                    name="gender"
                    id="gender"
                    v-model="registerData.gender"
                    :options="genders"
                    label="gender"
                    :deselectFromDropdown="true"
                    @option:deselected="registerData.gender = null"
                    @clear="registerData.gender = null"
                    :placeholder="$t('register.gender')"
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp">
              <ValidationProvider
                vid="birth_date"
                :name="$t('register.birthDate')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.birthDate") }}</b> <b class="red">*</b>
                <label for="birth_date">
                  <i class="fa fa-cake"></i>
                  <input
                    id="birth_date"
                    :placeholder="$t('register.birthDate')"
                    type="date"
                    v-model="registerData.birth_date"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="username"
                :name="$t('register.yourUsername')"
                rules="required|min:2|alpha_num|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.yourUsername") }}</b> <b class="red">*</b>
                <label for="username">
                  <i class="fa fa-user"></i>
                  <input
                    id="username"
                    type="text"
                    v-model="registerData.username"
                    :placeholder="$t('register.yourUsername')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="phone"
                :name="$t('register.phone')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.phone") }}</b> <b class="red">*</b>
                <label for="phone">
                  <i class="fa fa-mobile"></i>
                  <input
                    id="phone"
                    type="text"
                    v-model="registerData.phone"
                    :placeholder="$t('register.phone')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="city"
                :name="$t('register.city')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.city") }}</b> <b class="red">*</b>
                <label for="city">
                  <select
                    v-model="registerData.city"
                    name="city"
                    id="city"
                    @change.prevent="
                      getCities(registerData.city);
                      registerData.district = null;
                      registerData.neighborhood = null;
                      registerData.quarter = null;
                    "
                  >
                    <option
                      :value="null"
                      :selected="registerData.city === null"
                    >
                      {{ $t("register.pleaseSelectCity") }}
                    </option>
                    <option
                      :value="item.il_adi"
                      :key="index"
                      v-for="(item, index) in cities"
                      :selected="item.il_adi === registerData.city"
                    >
                      {{ item.il_adi }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="district"
                :name="$t('register.district')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.district") }}</b> <b class="red">*</b>
                <label for="district">
                  <select
                    class="form-control form-control-sm rounded-0"
                    v-model="registerData.district"
                    name="district"
                    id="district"
                    @change.prevent="
                      getDistricts(registerData.district);
                      registerData.neighborhood = null;
                      registerData.quarter = null;
                    "
                  >
                    <option :value="null">
                      {{ $t("register.pleaseSelectDistrict") }}
                    </option>
                    <option
                      :value="item.ilce_adi"
                      :key="index"
                      v-for="(item, index) in districts"
                      :selected="item.ilce_adi === registerData.district"
                    >
                      {{ item.ilce_adi }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="neighborhood"
                :name="$t('register.neighborhood')"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.neighborhood") }}</b>
                <label for="neighborhood">
                  <select
                    class="form-control form-control-sm rounded-0"
                    v-model="registerData.neighborhood"
                    name="neighborhood"
                    id="neighborhood"
                    @change.prevent="
                      getNeighborhoods(registerData.neighborhood);
                      registerData.quarter = null;
                    "
                  >
                    <option
                      :data-id="null"
                      :value="null"
                      :selected="registerData.neighborhood === null"
                    >
                      {{ $t("register.pleaseSelectNeighborhood") }}
                    </option>
                    <option
                      :value="item.semt_adi"
                      :key="index"
                      v-for="(item, index) in neighborhoods"
                      :selected="item.semt_adi === registerData.neighborhood"
                      :data-id="item.semt_id"
                    >
                      {{ item.semt_adi }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="quarter"
                :name="$t('register.quarter')"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.quarter") }}</b>
                <label for="quarter">
                  <select
                    class="form-control form-control-sm rounded-0"
                    v-model="registerData.quarter"
                    name="quarter"
                    id="quarter"
                  >
                    <option
                      :value="null"
                      :selected="registerData.quarter === null"
                    >
                      {{ $t("register.pleaseSelectQuarter") }}
                    </option>
                    <option
                      :value="item.mahalle_adi"
                      :key="index"
                      v-for="(item, index) in quarters"
                      :selected="item.mahalle_adi === registerData.quarter"
                    >
                      {{ item.mahalle_adi }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-group-inp mt-2">
          <ValidationProvider
            vid="address"
            :name="$t('register.address')"
            rules="min:2"
            v-slot="{ errors }"
            tag="div"
          >
            <b>{{ $t("register.address") }}</b>
            <label for="address">
              <i class="fa fa-map-marker-alt"></i>
              <textarea
                id="address"
                type="text"
                v-model="registerData.address"
                :placeholder="$t('register.address')"
              />
            </label>
            <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
        </div>
        <div class="pl-group-inp mt-2">
          <ValidationProvider
            vid="registerEmail"
            :name="$t('register.yourEmailAddress')"
            rules="required|min:2|email|max:255"
            v-slot="{ errors }"
            tag="div"
          >
            <b>{{ $t("register.yourEmailAddress") }}</b> <b class="red">*</b>
            <label for="registerEmail">
              <i class="fa fa-envelope-open"></i>
              <input
                id="registerEmail"
                type="email"
                v-model="registerData.email"
                :placeholder="$t('register.yourEmailAddress')"
                required
              />
            </label>
            <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="registerPassword"
                :name="$t('register.yourPassword')"
                rules="required|min:6|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.yourPassword") }}</b> <b class="red">*</b>
                <label for="registerPassword">
                  <i class="fa fa-lock"></i>
                  <input
                    id="registerPassword"
                    :placeholder="$t('register.yourPassword')"
                    type="password"
                    v-model="registerData.password"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="passwordRepeat"
                :name="$t('register.yourPasswordRepeat')"
                rules="required|min:6|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.yourPasswordRepeat") }}</b>
                <b class="red">*</b>
                <label for="passwordRepeat">
                  <i class="fa fa-lock"></i>
                  <input
                    id="passwordRepeat"
                    :placeholder="$t('register.yourPasswordRepeat')"
                    type="password"
                    v-model="registerData.passwordRepeat"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="type"
                :name="$t('register.type')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.type") }}</b> <b class="red">*</b>
                <label for="type">
                  <i class="fa fa-user-group"></i>
                  <select
                    name="type"
                    id="type"
                    v-model="registerData.type"
                    required
                  >
                    <option value="INDIVIDUAL">
                      {{ $t("register.individual") }}
                    </option>
                    <option value="CORPORATE">
                      {{ $t("register.corporate") }}
                    </option>
                  </select>
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6" v-if="registerData.type === 'CORPORATE'">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="company_name"
                :name="$t('register.companyName')"
                rules="required|min:2|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.companyName") }}</b> <b class="red">*</b>
                <label for="company_name">
                  <i class="fa fa-building"></i>
                  <input
                    id="company_name"
                    type="text"
                    v-model="registerData.company_name"
                    :placeholder="$t('register.companyName')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="pl-row" v-if="registerData.type === 'CORPORATE'">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="tax_number"
                :name="$t('register.taxNumber')"
                rules="required|min:10|max:12"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.taxNumber") }}</b> <b class="red">*</b>
                <label for="tax_number">
                  <i class="fa fa-building"></i>
                  <input
                    id="tax_number"
                    type="text"
                    v-model="registerData.tax_number"
                    :placeholder="$t('register.taxNumber')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="tax_administration"
                :name="$t('register.taxAdministration')"
                rules="required|min:2|max:70"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("register.taxAdministration") }}</b>
                <b class="red">*</b>
                <label for="tax_administration">
                  <i class="fa fa-building"></i>
                  <input
                    id="tax_administration"
                    type="text"
                    v-model="registerData.tax_administration"
                    :placeholder="$t('register.taxAdministration')"
                    required
                  />
                </label>
                <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <button
          ref="submitBtn"
          class="pl-buttons nbtn green-btn mt-3"
          type="submit"
          :disabled="invalid"
        >
          {{ $t("register.register") }}
        </button>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
  components: {
    vSelect,
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      cities: [],
      districts: [],
      neighborhoods: [],
      quarters: [],
      selectedCity: null,
      selectedDistrict: null,
      selectedNeighborhood: null,
      selectedQuarter: null,
      genders: ["Erkek", "Kadın", "Diğer"],
      registerData: {
        email: null,
        password: null,
        passwordRepeat: null,
        username: null,
        gender: null,
        birth_date: null,
        phone: null,
        first_name: null,
        last_name: null,
        city: null,
        district: null,
        neighborhood: null,
        quarter: null,
        address: null,
        type: "INDIVIDUAL",
        tax_number: null,
        tax_office: null,
        company_name: null,
        settings_id: 1,
      },
    };
  },
  methods: {
    getFormData(object) {
      return Object.keys(object).reduce((formData, key) => {
        if (object[key] !== null) {
          formData.append(
            key,
            Array.isArray(object[key])
              ? JSON.stringify(object[key])
              : object[key]
          );
        }
        return formData;
      }, new FormData());
    },
    /**
     * Refresh Token Login
     */
    async register() {
      try {
        this.$refs.submitBtn.disabled = true;
        this.registerData.settings_id = this.$store.state.settings.id;
        const formData = this.getFormData(this.registerData);
        let { data } = await this.$axios.post("register", formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        if (data.status) {
          this.$refs.submitBtn.disabled = false;
          this.$toast.success(data.message, this.$t("successfully"));
          this.$router.replace(this.localePath("/"));
        } else {
          this.$refs.submitBtn.disabled = false;
          this.$toast.error(data.message, this.$t("unsuccessfully"));
        }
      } catch (error) {
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("error"));
        }
      }
    },
    async getCities($city_name = null) {
      try {
        let url = "users/cities";
        if ($city_name) {
          let $params = new URLSearchParams({ city_name: $city_name });
          url += "?" + $params.toString();
        }
        let { data } = await this.$axios.get(url);
        if (data) {
          if ($city_name) {
            this.selectedCity = data.data;
            await this.getDistricts();
          } else {
            this.cities = data.data;
            this.districts = [];
            this.neighborhoods = [];
            this.quarters = [];
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getDistricts($district_name = null) {
      try {
        let url = "users/districts/";
        if (this.selectedCity) {
          url += this.selectedCity.id + "/";
        }
        if ($district_name !== null) {
          let $params = new URLSearchParams({ district_name: $district_name });
          url += "?" + $params.toString();
        }
        let { data } = await this.$axios.get(url);
        if (data) {
          if ($district_name) {
            this.selectedDistrict = data.data;
            await this.getNeighborhoods();
          } else {
            this.districts = data.data;
            this.neighborhoods = [];
            this.quarters = [];
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getNeighborhoods($neighborhood_name = null) {
      try {
        let url = "users/neighborhoods/";
        if (this.selectedDistrict) {
          url += this.selectedDistrict.id + "/";
        }
        if ($neighborhood_name !== null) {
          let $params = new URLSearchParams({
            neighborhood_name: $neighborhood_name,
          });
          url += "?" + $params.toString();
        }
        let { data } = await this.$axios.get(url);
        if (data) {
          if ($neighborhood_name) {
            this.selectedNeighborhood = data.data;
            await this.getQuarters();
          } else {
            this.neighborhoods = data.data;
            this.quarters = [];
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    async getQuarters($quarter_name = null) {
      try {
        let url = "users/quarters/";
        if (this.selectedNeighborhood) {
          url += this.selectedNeighborhood.id + "/";
        }
        if ($quarter_name !== null) {
          let $params = new URLSearchParams({
            quarter_name: $quarter_name,
          });
          url += "?" + $params.toString();
        }
        let { data } = await this.$axios.get(url);
        if (data) {
          if ($quarter_name) {
            this.selectedQuarter = data.data;
          } else {
            this.quarters = data.data;
          }
        }
      } catch (error) {
        console.log(error);
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
    this.getCities();
    /**
     * Run Twitter Login
     */
    if (this.$route.query.oauth_token && this.$route.query.oauth_verifier) {
      this.makeTwitterLogin();
    }
  },
};
</script>