<template>
  <div class="pl-main">
    <div v-if="polls.length">
      <div class="pl-question pl-question-pics" v-for="item in polls" :key="item.id">
        <div class="pl-question-cnt">
          <div class="pl-content">
            <!-- Poll Options -->
            <div class="pl-options">
              <div>
                <nuxt-link
                  :to="localePath('/categories/' + item.category_seo_url)"
                  :title="item.category_title"
                  rel="dofollow"
                >
                  <span class="bg-light text-dark fw-bold"
                    ><img
                      width="75"
                      loading="lazy"
                      class="lazyload img-fluid"
                      :data-src="
                        $config.UPLOADS_URL + '/categories/' + item.category_icon
                      "
                      :alt="item.category_title"
                    />
                    {{ item.category_title }}</span
                  >
                </nuxt-link>

                <a
                  rel="dofollow"
                  :title="$t('poll.pinned')"
                  href="javascript:void(0)"
                  v-if="parseInt(item.pinned)"
                  class="pt-pinned"
                  ><i class="fas fa-thumbtack" :title="$t('poll.pinned')"></i>
                  {{ $t("poll.pinned") }}</a
                >
              </div>
              <div>
                <ul>
                  <li v-if="item.author === $auth.user?.id">
                    <nuxt-link
                      rel="dofollow"
                      :to="localePath('/profile/statistics/' + item.seo_url)"
                      class="pl-plus-buttons pl-notext"
                      :title="$t('statistics.viewStatistics')"
                      ><i class="fas fa-chart-bar"></i
                    ></nuxt-link>
                  </li>
                  <li class="pl-share">
                    <span class="pl-plus-buttons pl-share-button"
                      ><i class="fas fa-share"></i><b>{{ $t("poll.share") }}</b></span
                    >
                    <ul class="dropdown">
                      <li>
                        <a
                          rel="dofollow"
                          :title="$t('poll.shareOnEmbed')"
                          class="pl-show-embed"
                          href="javascript:void(0)"
                          ><i class="fas fa-share bg-instagram embed"></i>
                          {{ $t("poll.shareOnEmbed") }}</a
                        >
                      </li>
                      <li>
                        <a
                          target="_blank"
                          rel="dofollow"
                          :title="$t('poll.shareOnFacebook')"
                          class="puerto-popup"
                          :href="
                            'https://www.facebook.com/sharer/sharer.php?u=' +
                            $config.BASE_URL +
                            localePath('/poll/' + item.seo_url)
                          "
                          ><i class="fab fa-facebook bg-facebook"></i>
                          {{ $t("poll.shareOnFacebook") }}</a
                        >
                      </li>
                      <li>
                        <a
                          target="_blank"
                          rel="dofollow"
                          :title="$t('poll.shareOnTwitter')"
                          class="puerto-popup"
                          :href="
                            'https://twitter.com/intent/tweet/?text=' +
                            encodeURIComponent(item.title) +
                            '&url=' +
                            $config.BASE_URL +
                            localePath('/poll/' + item.seo_url)
                          "
                          ><i class="fab fa-twitter bg-twitter"></i>
                          {{ $t("poll.shareOnTwitter") }}</a
                        >
                      </li>
                      <li>
                        <a
                          target="_blank"
                          rel="dofollow"
                          :title="$t('poll.shareOnEmail')"
                          class="puerto-popup"
                          :href="
                            'mailto:?Subject=' +
                            encodeURIComponent(item.title) +
                            '&Body=' +
                            encodeURIComponent(item.title) +
                            ' ' +
                            $config.BASE_URL +
                            localePath('/poll/' + item.seo_url)
                          "
                          ><i class="far fa-envelope bg-google"></i>
                          {{ $t("poll.shareOnEmail") }}</a
                        >
                      </li>
                      <li>
                        <a
                          target="_blank"
                          rel="dofollow"
                          :title="$t('poll.shareOnWhatsapp')"
                          class="puerto-popup"
                          :href="
                            'whatsapp://send?text=' +
                            $config.BASE_URL +
                            localePath('/poll/' + item.seo_url)
                          "
                          ><i class="fab fa-whatsapp bg-2"></i>
                          {{ $t("poll.shareOnWhatsapp") }}</a
                        >
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Poll IFrame -->
            <div class="pt-embed-form">
              <a
                rel="dofollow"
                title="Close"
                href="javascript:void(0)"
                class="pl-hide-embed"
                ><i class="fas fa-times"></i
              ></a>
              <pre class="radius">{{
                getIframeCode(
                  $config.BASE_URL.slice(0, -1) +
                    localePath("/poll/embed/" + item.seo_url)
                )
              }}</pre>
            </div>

            <!-- Poll Title -->
            <h3 class="pl-title">
              <nuxt-link
                rel="dofollow"
                :title="item.title"
                :to="localePath('/poll/' + item.seo_url)"
                >{{ item.title }}</nuxt-link
              >
            </h3>

            <!-- Poll Thumbnail -->
            <div class="pl-cover bg-white" v-if="item.images">
              <div class="row justify-content-center" v-viewer>
                <div
                  v-for="(image, index) in item.images"
                  :key="index"
                  class="mb-3"
                  :class="
                    'col-' +
                    (item.images.length === 4
                      ? '6'
                      : item.images.length === 3
                      ? '4'
                      : item.images.length === 2
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
                    :alt="item.title"
                  />
                </div>
              </div>
            </div>
            <!-- End Cover -->

            <!-- Poll Author -->
            <div class="pl-author" v-if="parseInt(item.role_id) !== 1">
              <div
                class="pl-thumb text-center d-flex align-items-center align-self-center align-content-center"
              >
                <nuxt-link
                  rel="dofollow"
                  :to="localePath('/profile/' + item.author_username)"
                  class="text-center d-flex align-items-center align-self-center align-content-center mx-auto"
                  data-bs-toggle="tooltip"
                  :data-bs-title="item.author_username"
                  data-bs-placement="right"
                >
                  <img
                    v-if="item.author_photo"
                    loading="lazy"
                    class="lazyload img-fluid"
                    :data-src="$config.UPLOADS_URL + '/users/' + item.author_photo"
                    :alt="item.author_username"
                  />
                  <i
                    class="fa fa-user-circle fa-2x text-center mx-auto"
                    :title="item.author_username"
                    v-else
                  ></i>

                  <i
                    v-if="item.author_type === 'CORPORATE'"
                    class="icon-check icons pl-verified"
                    :title="$t('corporateAccount')"
                  ></i>
                </nuxt-link>
              </div>
              <nuxt-link
                rel="dofollow"
                :to="localePath('/profile/' + item.author_username)"
                :title="item.author_username"
              >
                {{ item.author_username }}
              </nuxt-link>
            </div>

            <!-- Poll description -->
            <p class="pt-polldescription">{{ item.description }}</p>

            <!-- Poll Answers -->
            <div class="pl-answers" :class="answered(item?.id)">
              <div class="pl-row mx-0">
                <div
                  v-for="answer in item.answers"
                  :key="answer.id"
                  :class="
                    parseInt(item.polltype) === 1
                      ? 'pl-col-12'
                      : parseInt(item.polltype) === 2
                      ? 'pl-col-6'
                      : 'pl-col-4'
                  "
                  class="mb-2"
                >
                  <div
                    :class="[
                      'pl-radio',
                      parseInt(answer.voted_answer?.answer_id) === parseInt(answer.id)
                        ? 'pl-checked'
                        : null,
                      parseInt(item.polltype) === 2
                        ? 'pl-bool'
                        : parseInt(item.polltype) === 3
                        ? 'pl-pics'
                        : null,
                      new Date(item.end_date) < new Date() &&
                      answer.percentage === highestPercentage(item.id) && highestPercentage(item.id) !== 0
                        ? 'pl-checked bg-blue'
                        : null,
                    ]"
                  >
                    <input
                      :id="'answer' + answer.id"
                      type="radio"
                      name="pl-vote-inp[]"
                      :value="'answer' + answer.id"
                      :disabled="new Date(item.end_date) < new Date() || answer.voted"
                      @click.prevent="votePoll(item.id, answer.id)"
                    />
                    <label :for="'answer' + answer.id">
                      <small
                        v-if="answer.voted || new Date(item.end_date) < new Date()"
                        :class="'timer-' + answer.id"
                        class="timer"
                        :data-count="answer.percentage + '%'"
                        >{{ answer.percentage + "%" }}</small
                      >

                      <div class="pl-label-thumb" v-if="answer.thumbnail">
                        <img
                          loading="lazy"
                          class="lazyload img-fluid"
                          :data-src="
                            $config.UPLOADS_URL + '/polls/answers/' + answer.thumbnail
                          "
                          :alt="answer.answer"
                        />
                      </div>
                      {{
                        parseInt(item.polltype) === 2
                          ? $t("poll." + answer.answer)
                          : answer.answer
                      }}
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <!-- Poll End date -->

            <div v-if="new Date(item.end_date) < new Date()">
              <p class="pl-warning bg-danger text-white fw-bold p-3">
                <i class="icon-exclamation icons"></i>
                <span
                  >{{ $t("poll.expired") }}
                  <em>{{
                    new Date(item.end_date).toLocaleDateString("tr-TR") +
                    " " +
                    new Date(item.end_date).toLocaleTimeString("tr-TR")
                  }}</em
                  >!</span
                >
              </p>
            </div>

            <!-- Poll Details -->
            <div class="pl-details">
              <a
                v-if="isanswered(item.id)"
                href="javascript:void(0)"
                rel="dofollow"
                :title="$t('poll.addSmsList')"
                class="pl-plus-buttons pl-show-votes fw-bold bg-blue text-white"
                @click="
                  addSmsList(
                    $config.BASE_URL + 'poll/' ,
                    item.id
                  )
                "
                ><i class="fa fa-sms"></i> {{ $t("poll.addSmsList") }}</a
              >
              <nuxt-link
                rel="dofollow"
                :title="item.title"
                :to="localePath('/poll/' + item.seo_url)"
                class="pl-plus-buttons pl-show-votes d-none"
                ><i class="far fa-thumbs-up"></i><b>{{ item.votes }}</b>
                {{ $t("poll.voter") }}</nuxt-link
              >
              <nuxt-link
                rel="dofollow"
                :title="item.title"
                :to="localePath('/poll/' + item.seo_url)"
                class="pl-plus-buttons pl-show-replies"
                ><i class="far fa-comments"></i><b>{{ item.comments_count }}</b>
                {{ $t("poll.comment") }}</nuxt-link
              >
              <a
                href="javascript:void(0)"
                class="pl-plus-buttons pl-show-replies fw-bolder"
                rel="dofollow"
                :title="$t('poll.endDate')"
                >{{
                  $t("poll.endDate") +
                  " : " +
                  new Date(item.end_date).toLocaleDateString("tr-TR") +
                  " " +
                  new Date(item.end_date).toLocaleTimeString("tr-TR")
                }}</a
              >
            </div>
          </div>
          <!-- End Content -->
        </div>
        <!-- End pl-question-cnt -->

        <div class="pl-voters d-none" v-if="item.voters.length && $auth.loggedIn">
          <div class="pl-dtable">
            <div class="pl-vmiddle">
              <h3 class="pl-title">{{ $t("poll.voters") }}</h3>
            </div>
          </div>
          <ul>
            <li
              class="d-flex align-items-center align-self-center align-content-center text-center my-auto"
              v-for="voter in item.voters.slice(0, 5)"
              :key="voter.id"
            >
              <nuxt-link
                v-if="parseInt(voter.voterType) === 1"
                rel="dofollow"
                :to="localePath('/profile/' + voter.voter_username)"
                class="mx-auto"
                data-bs-toggle="tooltip"
                :data-bs-title="voter.voter_username"
                data-bs-placement="right"
                ><img
                  v-if="voter.voter_photo"
                  loading="lazy"
                  class="lazyload img-fluid"
                  :data-src="voter.voter_photo"
                  :alt="voter.voter_username"
                />
                <i class="fa fa-user-circle fa-2x mx-auto my-auto" v-else></i>
              </nuxt-link>
            </li>
          </ul>
          <div class="pl-dtable">
            <div class="pl-vmiddle">
              <nuxt-link
                rel="dofollow"
                :title="$t('poll.showAllVoters')"
                :to="localePath('/poll-voters/' + item.seo_url)"
                class="nbtn blue-btn lh45"
                ><i class="icon-plus icons"></i> {{ $t("poll.showAllVoters") }}</nuxt-link
              >
            </div>
          </div>
        </div>
        <div class="pl-not-found d-none" v-else>
          {{ !$auth.loggedIn ? $t("poll.loginToViewVoters") : $t("poll.noVotersFound") }}
        </div>
        <FrontendPollsComments
          @getPolls="getPolls"
          :poll="item"
          :comments="item?.comments"
          v-if="seo_url && item.comments"
          :key="$route.fullPath"
        />
      </div>
      <div
        class="col-sm-12 mt-3"
        v-if="recordsFiltered > polls.length && !isfrontend && !seo_url"
      >
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center text-center">
            <li :class="['page-item', { disabled: currentPage === 1 }]">
              <a
                rel="dofollow"
                title="Önceki Sayfa"
                class="page-link"
                @click.prevent="(currentPage -= 1), getPolls()"
                href="javascript:void(0)"
                aria-label="Previous"
              >
                <span aria-hidden="true" class="fa fa-angle-double-left"></span>
              </a>
            </li>
            <li
              :class="['page-item', { active: page === currentPage }]"
              v-for="page in totalPages"
              :key="page"
            >
              <a
                rel="dofollow"
                :title="page + '. Sayfa'"
                class="page-link"
                @click="
                  currentPage = page;
                  getPolls();
                "
                href="javascript:void(0)"
                >{{ page }}</a
              >
            </li>
            <li :class="['page-item', { disabled: currentPage === totalPages }]">
              <a
                rel="dofollow"
                title="Sonraki Sayfa"
                class="page-link"
                @click="(currentPage += 1), getPolls()"
                href="javascript:void(0)"
                aria-label="Next"
              >
                <span aria-hidden="true" class="fa fa-angle-double-right"></span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <div class="pl-not-found border shadow" v-else>
      {{ $t("poll.noPollsFound") }}
    </div>
  </div>
</template>

<script>
export default {
  name: "PollsList",
  props: {
    isprofile: {
      type: Boolean,
      default: false,
    },
    isfrontend: {
      type: Boolean,
      default: false,
    },
    category: {
      type: String,
      default: null,
    },
    seo_url: {
      type: String,
      default: null,
    },
    data_url: {
      type: String,
      default: "polls-list",
    },
  },
  watch: {
    "$route.query.search": {
      immediate: true,
      handler(value) {
        this.searchTerm = value;
        this.currentPage = 1;
        this.getPolls();
      },
    },
  },
  data() {
    return {
      searchTerm: this.$route.query.search || "",
      recordsFiltered: 0,
      recordsTotal: 0,
      polls: [],
      perPage: 12,
      totalPages: 0,
      currentPage: 1,
    };
  },
  mounted() {
    this.getPolls();
  },
  methods: {
    async addSmsList(url = null, poll_id = null) {
      try {
        const { data } = await this.$axios.post("add-sms-list", {
          url,
          poll_id,
        });
        if (data.status) {
          this.$toast.success(data.message, this.$t("successfully"));
        } else {
          this.$toast.error(data.message, this.$t("unsuccessfully"));
        }
      } catch (error) {
        console.log(error);
        if (error.response.data.message) {
          this.$toast.error(error.response.data.message, this.$t("unsuccessfully"));
        }
      }
    },
    isanswered(id = null) {
      let answered = false;
      this.polls.map((poll) => {
        if (parseInt(poll.id) === parseInt(id)) {
          return poll.answers.map((answer) => {
            if (
              answer.voted_answer !== null &&
              parseInt(answer.voted_answer?.answer_id) === parseInt(answer.id) &&
              parseInt(answer.voted_answer?.user_id) === parseInt(this.$auth.user?.id)
            ) {
              answered = true;
            }
          });
        }
      });
      return answered;
    },
    getIframeCode(url = null) {
      return `<iframe src="${url}" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>`;
    },
    answered(id = null) {
      let answered = false;
      this.polls.map((poll) => {
        if (parseInt(poll.id) === parseInt(id)) {
          return poll.answers.map((answer) => {
            if (
              answer.voted_answer !== null &&
              parseInt(answer.voted_answer?.answer_id) === parseInt(answer.id) 
            ) {
              answered = true;
            }
          });
        }
      });
      return answered ? "pl-answered" : null;
    },
    highestPercentage(id = null) {
      let highestPercentage = 0;
      this.polls.map((poll) => {
        if (parseInt(poll.id) === parseInt(id)) {
          return poll.answers.map((answer) => {
            if (answer.percentage > highestPercentage) {
              highestPercentage = answer.percentage;
            }
          });
        }
      });
      return highestPercentage;
    },
    async getPolls() {
      try {
        let url = this.data_url;
        if (this.seo_url) {
          url = "poll/" + this.seo_url;
        }
        let params = {
          searchTerm: this.searchTerm,
          start: this.currentPage - 1,
          perPage: this.perPage,
          page: this.currentPage,
          category: this.category,
        };
        if (this.$route.name === "polls-finished") {
          params.finished = true;
        }
        const { data } = await this.$axios.get(url, {
          params,
        });
        this.polls = data.data;

        this.polls.map(async (value) => {
          value.images = value.images ? JSON.parse(value.images) : null;
        });

        if (this.seo_url && this.polls.length) {
          this.$store.commit("SET_BREADCRUMB", {
            showBreadCrumb: true,
            breadCrumbTitle: this.polls[0].title,
            breadCrumbDescription: this.polls[0].title,
          });
        }

        this.recordsFiltered = data.recordsFiltered;
        this.recordsTotal = data.recordsTotal;
        this.totalPages = Math.ceil(data.recordsFiltered / this.perPage);
      } catch (error) {
        console.log(error);
      }
    },
    async votePoll(poll_id, answer_id) {
      try {
        const { data } = await this.$axios.post("vote-poll", {
          poll_id,
          answer_id,
        });
        if (data.status) {
          this.$toast.success(data.message, this.$t("successfully"));
          this.getPolls();
        }
      } catch (error) {
        console.log(error);
        this.$toast.error(error.response.data.message, this.$t("unsuccessfully"));
      }
    },
  },
  head() {
    return {
      bodyAttrs: {
        class: this.isprofile ? "pl-body-profile pl-body-questions" : "pl-body-questions",
      },
      title: this.polls.length === 1 ? this.polls[0].title : this.$t("poll.polls"),
    };
  },
};
</script>
