<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form
        @submit.prevent="handleSubmit(saveSlides)"
        enctype="multipart/form-data"
        method="POST"
      >
        <div class="row">
          <div class="col-sm-8">
            <div class="row mb-1">
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="title"
                    :name="$t('panel.slides.title')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="title" class="mb-5">{{
                      $t("panel.slides.title")
                    }}</label>
                    <input
                      id="title"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.slides.title')"
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
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="url"
                    :name="$t('panel.slides.url')"
                    rules="min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="url" class="mb-5">{{
                      $t("panel.slides.url")
                    }}</label>
                    <input
                      id="url"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.slides.url')"
                      type="text"
                      name="url"
                      v-model="formData.url"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>
            <div class="row mb-1">
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="description"
                    :name="$t('panel.slides.description')"
                    rules="min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="description" class="mb-5">{{
                      $t("panel.slides.description")
                    }}</label>
                    <textarea
                      id="description"
                      class="form-control form-control-sm rounded-0"
                      :placeholder="$t('panel.slides.description')"
                      type="text"
                      rows="5"
                      name="description"
                      v-model="formData.description"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="target"
                    :name="$t('panel.slides.target')"
                    rules="required|min:2"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="target" class="mb-5">{{
                      $t("panel.slides.target")
                    }}</label>
                    <select
                      name="target"
                      id="target"
                      class="form-control form-control-sm rounded-0"
                      v-model="formData.target"
                      required
                    >
                      <option value="_self">
                        {{ $t("panel.slides._self") }}
                      </option>
                      <option value="_blank">
                        {{ $t("panel.slides._blank") }}
                      </option>
                      <option value="_parent">
                        {{ $t("panel.slides._parent") }}
                      </option>
                      <option value="_top">
                        {{ $t("panel.slides._top") }}
                      </option>
                    </select>
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>
            <div class="row mb-1">
              <div class="col-sm-6" v-if="pages">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="page_id"
                    :name="$t('panel.slides.page')"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="page_id" class="mb-5">{{
                      $t("panel.slides.page")
                    }}</label>
                    <v-select
                      name="page_id"
                      id="page_id"
                      v-model="formData.page_id"
                      :options="pages"
                      label="title"
                      :reduce="(option) => option.id"
                      :deselectFromDropdown="true"
                      @option:deselected="formData.page_id = null"
                      @clear="formData.page_id = null"
                      :placeholder="$t('panel.slides.pleaseSelectPage')"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-sm-6" v-if="categories">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="category_id"
                    :name="$t('panel.slides.category')"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="category_id" class="mb-5">{{
                      $t("panel.slides.category")
                    }}</label>
                    <v-select
                      name="category_id"
                      id="category_id"
                      v-model="formData.category_id"
                      :options="categories"
                      label="title"
                      :reduce="(option) => option.id"
                      :deselectFromDropdown="true"
                      @clear="formData.category_id = null"
                      @option:deselected="formData.category_id = null"
                      :placeholder="$t('panel.slides.pleaseSelectCategory')"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
                </div>
              </div>
            </div>
            <div class="row mb-1">
              <div class="col-sm-6" v-if="polls">
                <div class="form-group my-1">
                  <ValidationProvider
                    vid="poll_id"
                    :name="$t('panel.slides.poll')"
                    v-slot="{ errors }"
                    tag="div"
                  >
                    <label for="poll_id" class="mb-5">{{
                      $t("panel.slides.poll")
                    }}</label>
                    <v-select
                      name="poll_id"
                      id="poll_id"
                      v-model="formData.poll_id"
                      :options="polls"
                      label="title"
                      :reduce="(option) => option.id"
                      :deselectFromDropdown="true"
                      @clear="formData.poll_id = null"
                      @option:deselected="formData.poll_id = null"
                      :placeholder="$t('panel.slides.pleaseSelectpoll')"
                    />
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
                    $config.API_URL + 'uploads/slides/' + formData.image
                  "
                  class="lazyload img-fluid p-3 bg-light"
                  :alt="$t('panel.slides.image')"
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
                    $t("panel.slides.pleaseSelectImage")
                  }}</label>
                  <input
                    class="form-control"
                    type="file"
                    id="formFile"
                    name="img_url"
                    @change="formData.img_url = $event.target.files[0]"
                    :placeholder="$t('panel.slides.pleaseSelectImage')"
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
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    vSelect,
  },
  props: ["id"],
  data() {
    return {
      categories: [],
      pages: [],
      polls: [],
      formData: {
        title: null,
        image: null,
        image_url: null,
        page_id: null,
        category_id: null,
        description: null,
        poll_id: null,
        target: null,
        url: null,
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
    async saveSlides() {
      try {
        const formData = this.getFormData(this.formData);
        let url = this.id
          ? "panel/slides/update/" + this.id
          : "panel/slides/save/";
        let { data } = await this.$axios.post(url, formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        data.status
          ? this.$toast.success(data.message, this.$t("successfully"))
          : this.$toast.error(data.message, this.$t("unsuccessfully"));
        await this.$store.dispatch("getSlides");
        setTimeout(() => {
          this.$router.replace(this.localePath("/panel/slides/"));
        }, 1000);
      } catch (error) {
        console.log(error);
      }
    },
    async getSlide(id) {
      try {
        let { data } = await this.$axios.get("panel/slides/" + id);
        if (data && data.data) {
          this.formData = data.data;
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
  mounted() {
    this.$axios.get("panel/pages").then((res) => {
      this.pages = res.data.data;
    });
    this.$axios.get("panel/polls").then((res) => {
      this.polls = res.data.data;
    });
    this.categories = this.$store.state.categories;
    if (this.id) {
      this.getSlide(this.id);
    }
  },
};
</script>