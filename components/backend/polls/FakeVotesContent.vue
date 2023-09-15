<template>
  <div>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
      <form
        @submit.prevent="handleSubmit(saveFakeVotes)"
        enctype="multipart/form-data"
        method="POST"
      >
        <div class="row mb-1">
          <div class="col-sm-8">
            <div class="form-group my-1">
              <ValidationProvider
                vid="answer"
                :name="$t('panel.polls.answers')"
                rules="required"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="answer" class="mb-5">{{
                  $t("panel.polls.answers")
                }}</label>
                <select
                  name="answer"
                  id="answer"
                  v-if="pollData.answers"
                  v-model="formData.answer"
                  class="form-control form-control-sm rounded-0"
                  required
                >
                  <option :value="null">
                    {{ $t("panel.polls.pleaseSelectAnswer") }}
                  </option>
                  <option
                    :value="answer.id"
                    v-for="answer in pollData.answers"
                    :key="answer.id"
                  >
                    {{
                      pollData.type === 2
                        ? $t("panel.polls." + answer.answer)
                        : answer.answer
                    }}
                  </option>
                </select>
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
              </ValidationProvider>
            </div>
          </div>
        </div>

        <div class="row mb-1">
          <div class="col-sm-8">
            <div class="form-group my-1">
              <ValidationProvider
                vid="fake_votes"
                :name="$t('panel.polls.howManyFakeVotes')"
                rules="required|min:2"
                v-slot="{ errors }"
                tag="div"
              >
                <label for="fake_votes" class="mb-5">{{
                  $t("panel.polls.howManyFakeVotes")
                }}</label>
                <input
                  id="fake_votes"
                  class="form-control form-control-sm rounded-0"
                  :placeholder="$t('panel.polls.howManyFakeVotes')"
                  type="text"
                  required
                  name="fake_votes"
                  v-model="formData.fake_votes"
                />
                <span class="mt-5 d-block text-danger">{{ errors[0] }}</span>
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
        answer: null,
        fake_votes: null,
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
    async saveFakeVotes() {
      try {
        const formData = this.getFormData(this.formData);
        let { data } = await this.$axios.post(
          "panel/poll-votes/add-fake-votes/" + this.id,
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