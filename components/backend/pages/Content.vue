<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form
        @submit.prevent="handleSubmit(savePages)"
        enctype="multipart/form-data"
        method="POST"
      >
        <div class="row">
          <div class="col-sm-8">
            <div class="row mb-1">
              <div class="col-sm-12">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="title"
                    :name="$t('panel.pages.title')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="title" class="mb-5">{{
                      $t("panel.pages.title")
                    }}</label>
                    <input
                      id="title"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.pages.title')"
                      type="text"
                      required
                      name="title"
                      v-model="formData.title"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-sm-12">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="description"
                    :name="$t('panel.pages.description')"
                    rules="min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="description" class="mb-5">{{
                      $t("panel.pages.description")
                    }}</label>
                    <textarea
                      id="description"
                      class="form-control form-control-sm rounded-0 tinymce"
                      :placeholder="$t('panel.pages.description')"
                      rows="5"
                      name="description"
                      v-model="formData.description"
                    ></textarea>
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="card mb-3">
              <div
                class="card-header d-flex text-center justify-content-center"
              >
                <img
                  v-if="formData.image"
                  loading="lazy"
                  :data-src="
                    $config.API_URL + 'uploads/pages/' + formData.image
                  "
                  class="lazyload p-3 bg-light img-fluid"
                  :alt="$t('panel.pages.image')"
                  width="250"
                  referrerpolicy="no-referrer"
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
                    $t("panel.pages.pleaseSelectImage")
                  }}</label>
                  <input
                    class="form-control"
                    type="file"
                    id="formFile"
                    name="img_url"
                    @change="formData.img_url = $event.target.files[0]"
                    :placeholder="$t('panel.pages.pleaseSelectImage')"
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
        image: null,
        img_url: null,
        description: null,
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
    async savePages() {
      try {
        const formData = this.getFormData(this.formData);
        let url = this.id
          ? "panel/pages/update/" + this.id
          : "panel/pages/save/";
        let { data } = await this.$axios.post(url, formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        data.status
          ? this.$toast.success(data.message, this.$t("successfully"))
          : this.$toast.error(data.message, this.$t("unsuccessfully"));
        await this.$store.dispatch("getPages");
        setTimeout(() => {
          this.$router.replace(this.localePath("/panel/pages/"));
        }, 1000);
      } catch (error) {
        console.log(error);
      }
    },
    async getPage(id) {
      try {
        let { data } = await this.$axios.get("panel/pages/" + id);
        if (data && data.data) {
          this.formData = data.data;
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
  mounted() {
    this.$nextTick(() => {
      setTimeout(() => {
        TinyMCEInit(this.$config.API_URL + "panel/");
      }, 1000);
    });

    if (this.id) {
      this.getPage(this.id);
    }
  },
};
</script>