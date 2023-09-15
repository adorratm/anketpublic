<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form @submit.prevent="handleSubmit(forgotPassword)">
        <h1 class="display-4 mb-10">
          {{ $t("panel.forgotPassword.forgotYourPassword") }}
        </h1>
        <p class="mb-30">
          {{ $t("panel.forgotPassword.resetYourPasswordWithYourInformation") }}
        </p>
        <div class="form-group">
          <ValidationProvider
            vid="email"
            :name="$t('yourEmailAddress')"
            rules="required|min:2|email"
            v-slot="{ errors }"
            tag="div"
          >
            <label for="email" class="mb-5">{{
              $t("panel.forgotPassword.yourEmailAddress")
            }}</label>
            <input
              id="email"
              class="form-control form-control-sm rounded-0"
              :placeholder="$t('panel.forgotPassword.yourEmailAddress')"
              type="email"
              v-model="email"
            />
            <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
          </ValidationProvider>
        </div>
        <button
          class="btn btn-pink btn-block btn-sm rounded-0"
          type="submit"
          :disabled="invalid"
        >
          {{ $t("panel.forgotPassword.resetMyPassword") }}
        </button>
        <p class="font-14 text-center mt-15">
          {{ $t("panel.forgotPassword.areYouRememberYourPassword") }}
        </p>
        <p class="text-center">
          <nuxt-link
            rel="dofollow"
            :title="$t('panel.forgotPassword.login')"
            :to="localePath('/panel/login')"
            >{{ $t("panel.forgotPassword.login") }}</nuxt-link
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
      email: null,
    };
  },
  methods: {
    async forgotPassword() {
      try {
        let response = await this.$axios.post();
        //this.$router.replace(this.localePath("/panel"));
      } catch (error) {
        console.log(error);
        this.$refs.form.setErrors({
          email: [error.response.data.message],
        });
      }
    },
  },
};
</script>