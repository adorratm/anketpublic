<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form
        @submit.prevent="handleSubmit(saveMenu)"
        enctype="multipart/form-data"
        method="POST"
      >
        <div class="row mb-1">
          <div class="col-sm-4">
            <div class="form-group my-1">
              <ValidationProvider
                vid="title"
                :name="$t('panel.menus.title')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="title" class="mb-5">{{
                  $t("panel.menus.title")
                }}</label>
                <input
                  id="title"
                  class="form-control form-control-sm rounded-0"
                  :placeholder="$t('panel.menus.title')"
                  type="text"
                  required
                  name="title"
                  v-model="formData.title"
                />
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group my-1">
              <ValidationProvider
                vid="url"
                :name="$t('panel.menus.url')"
                rules="min:1"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="url" class="mb-5">{{
                  $t("panel.menus.url")
                }}</label>
                <input
                  id="url"
                  class="form-control form-control-sm rounded-0"
                  :placeholder="$t('panel.menus.url')"
                  type="text"
                  name="url"
                  v-model="formData.url"
                />
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-4">
            <div class="form-group my-1">
              <ValidationProvider
                vid="top_id"
                :name="$t('panel.menus.topMenu')"
                rules="min:1"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="top_id" class="mb-5">{{
                  $t("panel.menus.topMenu")
                }}</label>
                <select
                  class="form-control form-control-sm rounded-0"
                  name="top_id"
                  id="top_id"
                  v-model="formData.top_id"
                >
                  <option :value="0" :selected="formData.top_id == 0">
                    {{ $t("panel.menus.pleaseSelectTopMenu") }}
                  </option>
                  <option
                    v-for="menu in menus"
                    :key="menu.id"
                    :value="menu.id"
                    :selected="menu.id === formData.top_id"
                  >
                    {{ menu.title }}
                  </option>
                </select>
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-4">
            <div class="form-group my-1">
              <ValidationProvider
                vid="position"
                :name="$t('panel.menus.position')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="position" class="mb-5">{{
                  $t("panel.menus.position")
                }}</label>
                <select
                  name="position"
                  id="position"
                  class="form-control form-control-sm rounded-0"
                  v-model="formData.position"
                  required
                >
                  <option value="HEADER">HEADER</option>
                  <option value="HEADER_RIGHT">HEADER RIGHT</option>
                  <option value="MOBILE">MOBILE</option>
                  <option value="FOOTER">FOOTER</option>
                  <option value="FOOTER2">FOOTER 2</option>
                </select>
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group my-1">
              <ValidationProvider
                vid="target"
                :name="$t('panel.menus.target')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="target" class="mb-5">{{
                  $t("panel.menus.target")
                }}</label>
                <select
                  name="target"
                  id="target"
                  class="form-control form-control-sm rounded-0"
                  v-model="formData.target"
                  required
                >
                  <option value="_self">{{ $t("panel.menus._self") }}</option>
                  <option value="_blank">{{ $t("panel.menus._blank") }}</option>
                  <option value="_parent">
                    {{ $t("panel.menus._parent") }}
                  </option>
                  <option value="_top">{{ $t("panel.menus._top") }}</option>
                </select>
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
        </div>

        <div class="row mb-1">
              <div class="col-sm-4" v-if="pages">
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
              <div class="col-sm-4" v-if="categories">
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
              <div class="col-sm-4" v-if="polls">
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
                      :placeholder="$t('panel.slides.pleaseSelectPoll')"
                    />
                    <span class="mt-5 d-block text-danger">{{
                      errors[0]
                    }}</span>
                  </ValidationProvider>
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
    vSelect
  },
  props: ["id"],
  computed: {
    menus() {
      return this.$store.state.menus;
    },
  },
  data() {
    return {
      categories: [],
      pages: [],
      polls: [],
      formData: {
        title: null,
        position: "HEADER",
        target: "_self",
        url: null,
        page_id: null,
        category_id:null,
        poll_id:null,
        top_id: 0,
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
    async saveMenu() {
      try {
        const formData = this.getFormData(this.formData);
        let url = this.id
          ? "panel/menus/update/" + this.id
          : "panel/menus/save/";
        let { data } = await this.$axios.post(url, formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        data.status
          ? this.$toast.success(data.message, this.$t("successfully"))
          : this.$toast.error(data.message, this.$t("unsuccessfully"));
          await this.$store.dispatch("getMenus");
        setTimeout(() => {
          this.$router.replace(this.localePath("/panel/menus/"));
        }, 1000);
      } catch (error) {
        console.log(error);
      }
    },
    async getMenu(id) {
      try {
        let { data } = await this.$axios.get("panel/menus/" + id);
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
      this.getMenu(this.id);
    }
  },
};
</script>