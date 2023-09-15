<template>
  <div class="pl-comments open">
    <div class="pl-comment-form mb-3" v-if="$auth.user">
      <div class="pl-thumb">
        <img
          v-if="$auth.user.picture"
          loading="lazy"
          class="lazyload img-fluid"
          :data-src="$config.UPLOADS_URL + '/users/' + $auth.user.picture"
          :alt="$auth.user.username"
        />
        <i class="fa fa-user mx-auto" v-else></i>
      </div>
      <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid }">
        <form
          class="pl-form pl-write-comment"
          @submit.prevent="handleSubmit(sendComment)"
        >
          <ValidationProvider
            vid="comment"
            :name="$t('poll.yourComment')"
            rules="required|min:2|max:255"
            v-slot="{ errors }"
            tag="div"
          >
            <textarea
              name="comment"
              :placeholder="$t('poll.writeYourComment')"
              required
              v-model="formData.comment"
              @keydown.enter="handleSubmit(sendComment)"
            ></textarea>
            <small class="d-block mt-1 text-danger">{{ errors[0] }}</small>
          </ValidationProvider>
          <hr />
          <button
            type="submit"
            class="pl-buttons nbtn green-btn pull-right"
            :disabled="invalid"
          >
            <i class="icon-cursor icons"></i> {{ $t("poll.sendComment") }}
          </button>
          <button class="nbtn greens-btn lh45 pull-right cancel">
            <i class="icon-close icons"></i> {{ $t("poll.cancel") }}
          </button>
        </form>
      </ValidationObserver>
    </div>
    <div class="pl-nouser text-center" v-else>
      {{ $t("poll.loginToWriteAComment") }}
    </div>
    <div v-if="comments.length">
      <div
        class="d-flex flex-row pl-comment"
        :key="comment.id"
        v-for="comment in comments"
      >
        <div class="p-2">
          <div
            class="pl-thumb text-center d-flex align-items-center align-self-center align-content-center"
          >
            <nuxt-link
              rel="dofollow"
              :title="comment.commenter_username"
              :to="localePath('/profile/' + comment.commenter_username)"
              class="d-flex align-items-center align-self-center align-content-center text-center mx-auto"
            >
              <img
                v-if="comment.commenter_photo"
                loading="lazy"
                class="lazyload img-fluid"
                :data-src="
                  $config.UPLOADS_URL + '/users/' + comment.commenter_photo
                "
                :alt="comment.commenter_username"
              />
              <i class="fa fa-2x fa-user-circle text-center mx-auto" v-else></i>
            </nuxt-link>
          </div>
        </div>
        <div class="p-2 flex-grow-1">
          <div class="pt-commentbox">
            <div class="pl-title">
              <nuxt-link
                rel="dofollow"
                :title="comment.commenter_username"
                :to="localePath('/profile/' + comment.commenter_username)"
                >{{ comment.commenter_username }}</nuxt-link
              >
              <span>{{ comment.updatedAt }}</span>
            </div>
            <div class="pl-cmt-content">{{ comment.comment }}</div>
          </div>
          <div class="pl-votes d-none">
            <span
              class="pl-commentvoteup<?php echo fh_vv_likes($cmtrs['id'], 1) ?>"
              
              ><i class="far fa-thumbs-up"></i> <b>1</b></span
            >
            <span
              class="pl-commentvotedown<?php echo fh_vv_likes($cmtrs['id'], 0) ?>"
              rel="<?php echo $cmtrs['id'] ?>"
              ><i class="far fa-thumbs-down"></i> <b>0</b></span
            >
          </div>
        </div>
      </div>
    </div>
    <div class="pl-nouser text-center" v-else>
      {{ $t("poll.noCommentsYet") }}
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  props: ["comments", "poll"],
  data() {
    return {
      formData: {
        comment: null,
        poll_id: null,
        user_id: null,
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
    async sendComment() {
      try {
        this.formData.poll_id = this.poll.id;
        this.formData.user_id = this.$auth?.user.id;
        const formData = this.getFormData(this.formData);
        let { data } = await this.$axios.post("send-comment", formData, {
          headers: {
            "Content-Type":
              "multipart/form-data; boundary=" + formData._boundary,
          },
        });
        data.status
          ? this.$toast.success(data.message, this.$t("successfully"))
          : this.$toast.error(data.message, this.$t("unsuccessfully"));
        this.formData.comment = null;
        this.$emit("getPolls");
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>