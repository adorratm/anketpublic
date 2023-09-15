<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form
        @submit.prevent="handleSubmit(savePoll)"
        enctype="multipart/form-data"
        method="POST"
      >
        <div class="row mb-1" v-if="pollData?.images?.length">
          <div class="col-sm-8">
            <div class="row justify-content-center" v-viewer>
              <div
                v-for="(image, index) in pollData?.images"
                :key="index"
                class="mb-3"
                :class="
                  'col-' +
                  (pollData?.images?.length === 4
                    ? '6'
                    : pollData?.images?.length === 3
                    ? '4'
                    : pollData?.images?.length === 2
                    ? '6'
                    : '12')
                "
              >
                <img
                  style="cursor: pointer"
                  v-if="image"
                  loading="lazy"
                  class="lazyload img-fluid rounded border"
                  :data-src="$config.UPLOADS_URL + '/polls/' + image"
                  :alt="pollData.title"
                />
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-8">
            <div class="form-group my-1">
              <ValidationProvider
                vid="title"
                :name="$t('panel.polls.title')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="title" class="mb-5">{{
                  $t("panel.polls.title")
                }}</label>
                <input
                  id="title"
                  class="form-control form-control-sm rounded-0"
                  :placeholder="$t('panel.polls.title')"
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
          <div class="col-sm-8">
            <div class="form-group my-1">
              <ValidationProvider
                vid="description"
                :name="$t('panel.polls.description')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="description" class="mb-5">{{
                  $t("panel.polls.description")
                }}</label>
                <textarea
                  id="description"
                  class="form-control form-control-sm rounded-0"
                  :placeholder="$t('panel.polls.description')"
                  required
                  name="description"
                  v-model="formData.description"
                  rows="5"
                />
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-8">
            <div class="form-group my-1">
              <ValidationProvider
                vid="end_date"
                :name="$t('panel.polls.endDate')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="end_date" class="mb-5">{{
                  $t("panel.polls.endDate")
                }}</label>
                <input
                  id="end_date"
                  class="form-control form-control-sm rounded-0"
                  :placeholder="$t('panel.polls.endDate')"
                  type="datetime-local"
                  required
                  name="end_date"
                  v-model="formData.end_date"
                />
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="row mb-1">
          <label
            >{{ $t("panel.polls.pollType") }} : 
            <b class="fw-bold text-danger">{{
              parseInt(pollData?.polltype) === 2
                ? $t("panel.polls.yesno")
                : parseInt(pollData?.polltype) === 1
                ? $t("panel.polls.normal")
                : $t("panel.polls.image")
            }}</b>
          </label>
        </div>
        <div
          class="row mb-1"
          v-if="
            parseInt(formData?.polltype) === 1 ||
            parseInt(formData?.polltype) === 3
          "
        >
          <div class="col-sm-8">
            <label class="d-block pl-answer-label mt-3 border p-3">
              <span class="fw-bold">
                {{ $t("poll.answers") }}
                <b class="text-danger red">*</b>
              </span>
              <div
                v-for="(answer, index) in formData.answers"
                :key="'answer' + index"
              >
                <div class="row mb-1">
                  <div
                    :class="
                      parseInt(formData?.polltype) === 3
                        ? formData?.answers?.length === 2
                          ? 'col-sm-8'
                          : 'col-sm-7'
                        : formData?.answers?.length === 2
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
                        class="form-control form-control-sm rounded-0"
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
                  <div
                    class="col-sm-4"
                    v-if="parseInt(formData.polltype) === 3"
                  >
                    <img
                      v-if="answer?.thumbnail"
                      loading="lazy"
                      class="lazyload img-fluid"
                      :data-src="
                        $config.UPLOADS_URL +
                        '/polls/answers/' +
                        answer?.thumbnail
                      "
                      :alt="answer.answer"
                    />
                    <input
                      class="form-control form-control-sm rounded-0"
                      :id="'thumbnail' + index"
                      :placeholder="$t('poll.thumbnail') + ' ' + (index + 1)"
                      type="file"
                      name="thumbnails[]"
                      @change="answer.thumbnail = $event.target.files[0]"
                    />
                  </div>
                  <div class="col-sm-1" v-if="formData?.answers?.length > 2">
                    <button
                      class="btn btn-danger btn-sm w-100"
                      @click.prevent="formData.answers.splice(index, 1)"
                    >
                      <i class="fas fa-times-circle"></i>
                    </button>
                  </div>
                </div>
              </div>
            </label>
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
      pollData: {},
      formData: {
        title: null,
        description: null,
        end_date: null,
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
    async savePoll() {
      try {
        const formData = this.getFormData(this.formData);
        this.formData.answers.map((a) => {
          formData.append("thumbnails[]", a.thumbnail);
        });
        let { data } = await this.$axios.post(
          "panel/polls/update/" + this.id,
          formData,
          {
            headers: {
              "Content-Type":
                "multipart/form-data; boundary=" + formData._boundary,
            },
          }
        );
        data.status
          ? this.$toast.success(data.message, this.$t("successfully"))
          : this.$toast.error(data.message, this.$t("unsuccessfully"));
        setTimeout(() => {
          this.$router.replace(this.localePath("/panel/polls/"));
        }, 1000);
      } catch (error) {
        console.log(error);
        if(error.response.data.message){
          this.$toast.error(error.response.data.message, this.$t("unsuccessfully"));
        }
      }
    },
    async getPoll(id) {
      try {
        let { data } = await this.$axios.get("panel/poll/" + id);
        if (data && data.data) {
          this.pollData = data.data[0];
          this.pollData.images = this.pollData.images
            ? JSON.parse(this.pollData.images)
            : [];
          this.formData = {
            title: this.pollData.title,
            description: this.pollData.description,
            end_date: this.pollData.end_date,
            polltype: this.pollData.polltype,
            answers: this.pollData.answers,
          };
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
  mounted() {
    if (this.id) {
      this.getPoll(this.id);
    }
  },
};
</script>