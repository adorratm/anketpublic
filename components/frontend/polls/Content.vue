<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form
        class="pl-form"
        id="send-question"
        @submit.prevent="handleSubmit(runForm)"
        enctype="multipart/form-data"
        method="POST"
      >
        <div class="pl-social-login">
          <Dropzone
            id="images"
            ref="images"
            :options="options"
            :destroyDropzone="true"
            multiple
            @vdropzone-sending="savePoll"
            :useCustomSlot="true"
          >
            <template v-slot:default>
              <div class="dz-message needsclick">
                <i class="fa fa-cloud-upload fa-3x"></i>
                <h5>{{ $t("poll.dropzone") }}</h5>
                <small>{{ $t("poll.maxImage") }}</small>
              </div>
            </template>
          </Dropzone>
        </div>
        <ValidationProvider
          vid="title"
          :name="$t('poll.title')"
          rules="required|min:2"
          v-slot="{ errors }"
          tag="div"
        >
          <label for="title" class="mt-3">
            {{ $t("poll.title") }} <b class="red">*</b>
            <input
              id="title"
              :placeholder="$t('poll.title')"
              type="text"
              required
              name="title"
              v-model="formData.title"
            />
          </label>
          <small class="d-block text-danger">{{ errors[0] }}</small>
        </ValidationProvider>
        <ValidationProvider
          vid="category_id"
          :name="$t('poll.category')"
          v-slot="{ errors }"
          tag="div"
          rules="required"
        >
          <label for="category_id" class="mt-3"
            >{{ $t("poll.category") }} <b class="red">*</b>
          </label>
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
            :placeholder="$t('poll.pleaseSelectCategory')"
          />
          <small class="d-block text-danger">{{ errors[0] }}</small>
        </ValidationProvider>
        <ValidationProvider
          vid="description"
          :name="$t('poll.description')"
          rules="min:2"
          v-slot="{ errors }"
          tag="div"
        >
          <label for="description" class="mt-3">
            {{ $t("poll.description") }}
            <textarea
              class="mb-0"
              id="description"
              :placeholder="$t('poll.description')"
              type="text"
              rows="5"
              name="description"
              v-model="formData.description"
            />
          </label>
          <small class="d-block text-danger">{{ errors[0] }}</small>
        </ValidationProvider>
        <ValidationProvider
          vid="end_date"
          :name="$t('poll.endDate')"
          rules="required"
          v-slot="{ errors }"
          tag="div"
        >
          <label for="end_date" class="mt-3">
            {{ $t("poll.endDate") }} <b class="red">*</b>
            <input
              id="end_date"
              :placeholder="$t('poll.endDate')"
              type="datetime-local"
              required
              name="end_date"
              v-model="formData.end_date"
            />
          </label>
          <small class="d-block text-danger">{{ errors[0] }}</small>
        </ValidationProvider>

        <label> {{ $t("poll.type") }} <b class="red">*</b> </label>
        <div class="pl-row pl-question-type">
          <div class="pl-col-4">
            <div class="pl-radio">
              <input
                id="polltype"
                :checked="parseInt(formData.polltype) === 1"
                type="radio"
                name="polltype"
                v-model="formData.polltype"
                :value="1"
              />
              <label for="polltype">{{ $t("poll.normal") }}</label>
            </div>
          </div>
          <div class="pl-col-4">
            <div class="pl-radio">
              <input
                id="polltype_2"
                :checked="parseInt(formData.polltype) === 2"
                type="radio"
                name="polltype"
                v-model="formData.polltype"
                :value="2"
              />
              <label for="polltype_2">{{ $t("poll.yesno") }}</label>
            </div>
          </div>
          <div class="pl-col-4">
            <div class="pl-radio">
              <ValidationProvider
                vid="polltype"
                rules="oneOf:1,2,3|required"
                name="polltype"
                v-slot="{ errors }"
              >
                <input
                  id="polltype_3"
                  :checked="parseInt(formData.polltype) === 3"
                  type="radio"
                  name="polltype"
                  v-model="formData.polltype"
                  :value="3"
                />
                <label for="polltype_3">{{ $t("poll.image") }}</label>
                <small class="d-block text-danger">{{ errors[0] }}</small>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <label
          v-if="parseInt(formData.polltype) === 1 || parseInt(formData.polltype) === 3"
          class="pl-answer-label mt-3"
        >
          <span>
            {{ $t("poll.answers") }}
            <b class="red">*</b>
          </span>
          <div v-for="(answer, index) in formData.answers" :key="'answer' + index">
            <div class="row">
              <div
                :class="
                  parseInt(formData.polltype) === 3
                    ? formData.answers.length === 2
                      ? 'col-sm-8'
                      : 'col-sm-7'
                    : formData.answers.length === 2
                    ? 'col-sm-12'
                    : 'col-sm-11'
                "
              >
                <ValidationProvider
                  vid="answer"
                  :name="$t('poll.answer')"
                  rules="required|min:2"
                  v-slot="{ errors }"
                  tag="span"
                >
                  <input
                    :id="'answer' + index"
                    :placeholder="$t('poll.answer') + ' ' + (index + 1)"
                    type="text"
                    required
                    name="answers[]"
                    v-model="answer.answer"
                  />
                  <small class="d-block text-danger">{{ errors[0] }}</small>
                </ValidationProvider>
              </div>
              <div class="col-sm-4" v-if="parseInt(formData.polltype) === 3">
                <input
                  :id="'thumbnail' + index"
                  :placeholder="$t('poll.thumbnail') + ' ' + (index + 1)"
                  type="file"
                  required
                  name="thumbnails[]"
                  @change="answer.thumbnail = $event.target.files[0]"
                />
              </div>
              <div class="col-sm-1" v-if="formData.answers.length > 2">
                <button
                  style="height: 42px"
                  class="btn btn-danger w-100"
                  @click.prevent="formData.answers.splice(index, 1)"
                >
                  <i class="fas fa-times-circle"></i>
                </button>
              </div>
            </div>
          </div>
          <button
            class="pl-add-answer btn btn-danger float-end"
            @click.prevent="formData.answers.push({ answer: null, thumbnail: null })"
            v-if="formData.answers.length < 12"
          >
            {{ $t("poll.addField") }}
            <i class="fa fa-plus-circle my-0"></i>
          </button>
        </label>

        <button
          class="pl-buttons nbtn green-btn mt-3"
          type="submit"
          ref="submitButton"
          :disabled="invalid"
        >
          {{ $t("panel.save") }} <i class="fas fa-arrow-circle-right"></i>
        </button>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import Dropzone from "nuxt-dropzone";
import "nuxt-dropzone/dropzone.css";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";
export default {
  components: {
    vSelect,
    Dropzone,
    ValidationProvider,
    ValidationObserver,
  },
  props: ["id"],
  data() {
    return {
      //
      categories: [],
      options: {
        method: "POST",
        dictDefaultMessage: this.$t("poll.dropzone"),
        uploadMultiple: true,
        parallelUploads: 4,
        maxFiles: 4,
        autoProcessQueue: false,
        headers: {
          "Cache-Control": null,
          "X-Requested-With": null,
          Authorization: null,
        },
        url: this.$config.API_URL + (this.id ? "polls/update/" + this.id : "polls/save/"),
        addRemoveLinks: true,
      },
      formData: {
        title: null,
        description: null,
        polltype: null,
        end_date: null,
        answers: [
          {
            answer: null,
            thumbnail: null,
          },
          {
            answer: null,
            thumbnail: null,
          },
        ],
      },
    };
  },
  methods: {
    getFormData(object) {
      return Object.keys(object).reduce((formData, key) => {
        if (object[key] !== null) {
          formData.append(
            key,
            Array.isArray(object[key]) ? JSON.stringify(object[key]) : object[key]
          );
        }
        return formData;
      }, new FormData());
    },
    savePoll(file, xhr, formData) {
      try {
        this.$refs.submitButton.setAttribute("disabled", "disabled");
        formData.append("title", this.formData.title);
        formData.append("description", this.formData.description);
        formData.append("polltype", this.formData.polltype);
        formData.append("end_date", this.formData.end_date);
        formData.append("category_id", this.formData.category_id);
        formData.append(
          "answers",
          JSON.stringify(this.formData.answers.map((a) => a.answer))
        );
        this.formData.answers.map((a) => {
          formData.append("thumbnails[]", a.thumbnail);
        });
        if (xhr) {
          xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
              let data = JSON.parse(xhr.response);
              data.status
                ? this.$toast.success(data.message, this.$t("successfully"))
                : this.$toast.error(data.message, this.$t("unsuccessfully"));
              this.$refs.submitButton.removeAttribute("disabled");
              if (data.status) {
                setTimeout(() => {
                  this.$router.replace(this.localePath("/polls/"));
                }, 1000);
              }
            }
          };
        } else {
          xhr = new XMLHttpRequest();
          xhr.open(
            "POST",
            this.$config.API_URL + (this.id ? "polls/update/" + this.id : "polls/save/")
          );
          xhr.setRequestHeader(
            "Authorization",
            localStorage.getItem("auth.strategy") === "user"
              ? localStorage.getItem("auth._token.user")
              : localStorage.getItem("auth._token.admin")
          );
          xhr.setRequestHeader("X-Requested-With", null);
          xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
              let data = JSON.parse(xhr.response);
              data.status
                ? this.$toast.success(data.message, this.$t("successfully"))
                : this.$toast.error(data.message, this.$t("unsuccessfully"));
              this.$refs.submitButton.removeAttribute("disabled");
              if (data.status) {
                setTimeout(() => {
                  this.$router.replace(this.localePath("/polls/"));
                }, 1000);
              }
            }
          };
          xhr.send(formData);
        }
      } catch (error) {
        console.log(error);
      }
    },
    runForm() {
      if (this.$refs.images.getAcceptedFiles().length > 0) {
        this.$refs.images.processQueue();
      } else {
        this.savePoll(null, null, new FormData());
      }
    },
  },
  mounted() {
    this.categories = this.$store.state.categories;
    this.$nextTick(() => {
      this.options.headers.Authorization =
        localStorage.getItem("auth.strategy") === "user"
          ? localStorage.getItem("auth._token.user")
          : localStorage.getItem("auth._token.admin");
    });
  },
};
</script>
