<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form
        @submit.prevent="handleSubmit(saveCategories)"
        enctype="multipart/form-data"
        method="POST"
      >
        <div class="row mb-1">
          <div class="col-sm-4">
            <div class="form-group my-1">
              <ValidationProvider
                vid="title"
                :name="$t('panel.categories.title')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="title" class="mb-5">{{
                  $t("panel.categories.title")
                }}</label>
                <input
                  id="title"
                  class="form-control form-control-sm rounded-0"
                  :placeholder="$t('panel.categories.title')"
                  type="text"
                  required
                  name="title"
                  v-model="formData.title"
                />
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-4">
            <div class="card mb-3">
              <div
                class="card-header d-flex text-center justify-content-center"
              >
                <img
                  v-if="formData.icon"
                  loading="lazy"
                  :data-src="
                    $config.UPLOADS_URL + 'categories/' + formData.icon
                  "
                  class="lazyload rounded-circle p-3 bg-light img-fluid"
                  :alt="$t('panel.categories.icon')"
                  width="150"
                />
                <i
                  v-else
                  class="fa fa-folder-open fa-3x text-center bg-light p-3 rounded-circle"
                ></i>
              </div>
              <div class="card-body text-center">
                <h5 class="card-title">
                  {{ formData.title }}
                </h5>
                <div class="mb-3">
                  <label for="formFile" class="form-label">{{
                    $t("panel.categories.pleaseSelectIcon")
                  }}</label>
                  <input
                    class="form-control"
                    type="file"
                    id="formFile"
                    name="icon"
                    @change="formData.icon_url = $event.target.files[0]"
                    :placeholder="$t('panel.categories.pleaseSelectIcon')"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-4">
            <div class="card mb-3">
              <div
                class="card-header d-flex text-center justify-content-center"
              >
                <img
                  v-if="formData.bg"
                  loading="lazy"
                  :data-src="
                    $config.UPLOADS_URL + 'categories/' + formData.bg
                  "
                  class="lazyload img-fluid p-3 bg-light"
                  :alt="$t('panel.categories.bg')"
                  referrerpolicy="no-referrer"
                />
                <i
                  v-else
                  class="fa fa-image fa-3x text-center bg-light p-3 rounded-circle"
                ></i>
              </div>
              <div class="card-body text-center">
                <h5 class="card-title">
                  {{ formData.title }}
                </h5>
                <div class="mb-3">
                  <label for="formBg" class="form-label">{{
                    $t("panel.categories.pleaseSelectBg")
                  }}</label>
                  <input
                    class="form-control"
                    type="file"
                    id="formBg"
                    name="bg"
                    @change="formData.bg_url = $event.target.files[0]"
                    :placeholder="$t('panel.categories.pleaseSelectBg')"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-4">
            <div class="form-group my-1">
              <button
                class="btn btn-pink btn-sm rounded-0"
                type="submit"
                :disabled="invalid"
              >
                {{ $t("panel.save") }}
              </button>
            </div>
          </div>
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
  props: ["id"],
  data() {
    return {
      formData: {
        title: null,
        icon: null,
        icon_url: null,
        bg: null,
        bg_url: null,
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
    async saveCategories() {
      try {
        const formData = this.getFormData(this.formData);
        let url = this.id
          ? "panel/categories/update/" + this.id
          : "panel/categories/save/";
        let { data } = await this.$axios.post(url, formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        data.status
          ? this.$toast.success(data.message, this.$t("successfully"))
          : this.$toast.error(data.message, this.$t("unsuccessfully"));
        await this.$store.dispatch("getCategories");
        setTimeout(() => {
          this.$router.replace(this.localePath("/panel/categories/"));
        }, 1000);
      } catch (error) {
        console.log(error);
      }
    },
    async getCategory(id) {
      try {
        let { data } = await this.$axios.get("panel/categories/" + id);
        if (data && data.data) {
          this.formData = data.data;
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
  mounted() {
    if (this.id) {
      this.getCategory(this.id);
    }
  },
};
</script>