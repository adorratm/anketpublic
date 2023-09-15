<template>
  <div class="pl-signup" id="pl-signup">
    <h4 class="pl-page-head">
      {{ $t("forgotPassword.forgotPassword") }}
      <i class="fas fa-lock"></i>
    </h4>
    <hr />
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form class="pl-form" @submit.prevent="handleSubmit(forgotPassword)">
        <div class="pl-group-inp" v-show="showEmail">
          <ValidationProvider
            vid="forgotPasswordEmail"
            :name="$t('forgotPassword.yourEmailAddress')"
            rules="required|min:2|email|max:255"
            v-slot="{ errors }"
            tag="div"
          >
            <b>{{ $t("forgotPassword.yourEmailAddress") }}</b>
            <b class="red">*</b>
            <label for="forgotPasswordEmail">
              <i class="fa fa-envelope-open"></i>
              <input
                id="forgotPasswordEmail"
                type="email"
                v-model="formData.email"
                :placeholder="$t('forgotPassword.yourEmailAddress')"
                required
              />
            </label>
            <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
        </div>
        <div class="pl-group-inp mt-2" v-if="showToken">
          <ValidationProvider
            vid="forgotPasswordToken"
            :name="$t('forgotPassword.token')"
            rules="required|min:9|max:9"
            v-slot="{ errors }"
            tag="div"
          >
            <b>{{ $t("forgotPassword.token") }}</b>
            <b class="red">*</b>
            <label for="forgotPasswordToken">
              <i class="fa fa-key"></i>
              <input
                id="forgotPasswordToken"
                type="text"
                v-model="formData.token"
                :placeholder="$t('forgotPassword.token')"
                required
              />
            </label>
            <small class="mt-1 d-block text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
        </div>
        <div class="pl-row" v-if="showPassword">
          <div class="pl-col-6">
            <div class="pl-group-inp mt-2">
              <ValidationProvider
                vid="forgotPasswordPassword"
                :name="$t('forgotPassword.yourPassword')"
                rules="required|min:6|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("forgotPassword.yourPassword") }}</b>
                <b class="red">*</b>
                <label for="forgotPasswordPassword">
                  <i class="fa fa-lock"></i>
                  <input
                    id="forgotPasswordPassword"
                    :placeholder="$t('forgotPassword.yourPassword')"
                    type="password"
                    v-model="formData.password"
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
                :name="$t('forgotPassword.yourPasswordRepeat')"
                rules="required|min:6|max:255"
                v-slot="{ errors }"
                tag="div"
              >
                <b>{{ $t("forgotPassword.yourPasswordRepeat") }}</b>
                <b class="red">*</b>
                <label for="passwordRepeat">
                  <i class="fa fa-lock"></i>
                  <input
                    id="passwordRepeat"
                    :placeholder="$t('forgotPassword.yourPasswordRepeat')"
                    type="password"
                    v-model="formData.passwordRepeat"
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
          {{ $t("forgotPassword.resetMyPassword") }}
        </button>
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
      url: "forgot-password",
      showEmail: true,
      showToken: false,
      showPassword: false,
      formData: {
        email: null,
        password: null,
        passwordRepeat: null,
        token: null,
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
    async forgotPassword() {
      try {
        this.$refs.submitBtn.disabled = true;
        this.formData.settings_id = this.$store.state.settings.id;
        const formData = this.getFormData(this.formData);
        let { data } = await this.$axios.post(this.url, formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        if (data.status) {
          this.url = "forgot-password-reset";
          this.showEmail = false;
          this.showToken = true;
          this.showPassword = true;
          this.$refs.submitBtn.disabled = false;
          this.$toast.success(data.message, this.$t("successfully"));
          if (data.data) {
            this.$router.replace(this.localePath("/"));
          }
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
  },
};
</script>