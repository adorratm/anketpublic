<template>
  <div class="col-lg-12">
    <div class="pl-main pl-statistics" v-if="poll">
      <div class="pl-cpanel-heading">
        <div class="pl-row">
          <div class="pl-col-9 pl-cpanel-title">
            <h3>
              <i class="icon-chart icons"></i>{{ $t("statistics.statistics") }}
            </h3>
            <p>
              <b>{{ poll.title }}</b> {{ $t("statistics.youAreViewing") }}
            </p>
          </div>
          <div class="pl-col-3 text-end">
            <a
              rel="dofollow"
              :title="$t('statistics.exportToPdf')"
              href="javascript:void(0)"
              :data-name="poll.title"
              class="nbtn blue-btn stats-download"
              ><i class="fas fa-file-pdf"></i>
              {{ $t("statistics.exportToPdf") }}</a
            >
          </div>
        </div>
      </div>
      <div class="pl-box p-4" id="root">
        <div>
          <h3>{{ poll.title }}</h3>
          <p class="pl-chart-content">
            <i class="fa fa-thumbs-up"></i> {{ poll.votes_count }}
            {{ $t("totalVotes") }} <i class="fa fa-comments ml-3"></i>
            {{ poll.comments_count }} {{ $t("totalComments") }}
          </p>
        </div>

        <h5 class="cp-stats-h5">{{ $t("statistics.statisticsByVotes") }}</h5>
        <hr />
        <div v-if="poll.answers?.length">
          <div
            class="pt-answer-stats"
            v-for="answer in poll.answers"
            :key="answer.id"
          >
            <div class="mb-3">
              <b class="d-block">{{
                parseInt(poll.polltype) === 2
                  ? $t("poll." + answer.answer)
                  : answer.answer
              }}</b>
              <img
                v-if="answer.thumbnail"
                loading="lazy"
                class="lazyload img-fluid"
                :data-src="
                  $config.UPLOADS_URL + '/polls/answers/' + answer.thumbnail
                "
                width="300"
                :alt="
                  parseInt(poll.polltype) === 2
                    ? $t('poll.' + answer.answer)
                    : answer.answer
                "
              />
              <div class="pt-l1">
                <span
                  class="pt-l2 d-block"
                  :style="
                    'width: ' +
                    answer.percentage +
                    '%; background: #' +
                    answer.color.hex
                  "
                >
                  <small
                    class="px-3 fw-bold"
                    :style="'color:#fff;background: #' + answer.color.hex"
                    >{{ answer.percentage }}%</small
                  >
                </span>
              </div>
            </div>
          </div>
        </div>
        <hr />

        <h5 class="cp-stats-h5">{{ $t("statistics.statisticsByGender") }}</h5>

        <table class="table table-bordered">
          <tbody>
            <tr>
              <td width="85%" class="td-m">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td width="85%">{{ $t("register.male") }}</td>
                      <td width="15%" class="text-center">
                        {{ poll.genders?.male }}%
                      </td>
                    </tr>
                    <tr>
                      <td width="85%">{{ $t("register.female") }}</td>
                      <td width="15%" class="text-center">
                        {{ poll.genders?.female }}%
                      </td>
                    </tr>
                    <tr>
                      <td width="85%">{{ $t("register.other") }}</td>
                      <td width="15%" class="text-center">
                        {{ poll.genders?.other }}%
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
              <td width="15%" class="td-m">
                <div class="pt-charts">
                  <div class="pt-question-gender">
                    <canvas id="question-gender"></canvas>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <h5 class="cp-stats-h5 html2pdf__page-break">
          {{ $t("statistics.statisticsByAge") }}
        </h5>
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td width="85%" class="td-m">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td width="85%">{{ "<13" }}</td>
                      <td width="15%" class="text-center text-nowrap">
                        {{ poll.ages?.age_13 }} {{ $t("statistics.vote") }} ({{
                          poll.ages?.age_13_percentage
                        }}%)
                      </td>
                    </tr>
                    <tr>
                      <td width="85%">13-18</td>
                      <td width="15%" class="text-center text-nowrap">
                        {{ poll.ages?.age_13_18 }}
                        {{ $t("statistics.vote") }} ({{
                          poll.ages?.age_13_18_percentage
                        }}%)
                      </td>
                    </tr>
                    <tr>
                      <td width="85%">18-25</td>
                      <td width="15%" class="text-center text-nowrap">
                        {{ poll.ages?.age_18_25 }}
                        {{ $t("statistics.vote") }} ({{
                          poll.ages?.age_18_25_percentage
                        }}%)
                      </td>
                    </tr>
                    <tr>
                      <td width="85%">25-35</td>
                      <td width="15%" class="text-center text-nowrap">
                        {{ poll.ages?.age_25_35 }}
                        {{ $t("statistics.vote") }} ({{
                          poll.ages?.age_25_35_percentage
                        }}%)
                      </td>
                    </tr>
                    <tr>
                      <td width="85%">35-50</td>
                      <td width="15%" class="text-center text-nowrap">
                        {{ poll.ages?.age_35_50 }}
                        {{ $t("statistics.vote") }} ({{
                          poll.ages?.age_35_50_percentage
                        }}%)
                      </td>
                    </tr>
                    <tr>
                      <td width="85%">+50</td>
                      <td width="15%" class="text-center">
                        {{ poll.ages?.age_50 }} {{ $t("statistics.vote") }} ({{
                          poll.ages?.age_50_percentage
                        }}%)
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
              <td width="15%" class="td-m">
                <div class="pt-charts">
                  <div class="pt-question-age">
                    <canvas id="question-age"></canvas>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <h5
          class="cp-stats-h5 html2pdf__page-break"
          v-if="poll.cities_and_districts?.length"
        >
          {{ $t("statistics.statisticsByCityDistrict") }}
        </h5>
        <table
          class="table table-bordered"
          v-if="poll.cities_and_districts?.length"
        >
          <tbody>
            <tr
              v-for="(cities_and_districts, index) in poll.cities_and_districts"
              :key="index"
            >
              <td width="85%">
                <span>
                  {{ cities_and_districts.city }} -
                  {{ cities_and_districts.district }}
                </span>
              </td>
              <td width="15%" class="text-center">
                {{ cities_and_districts.percentage }} %
              </td>
            </tr>
          </tbody>
        </table>

        <h5 class="cp-stats-h5 html2pdf__page-break" v-if="poll.voters?.length">
          {{ $t("statistics.listOfVoters") }}
        </h5>
        <div class="table-responsive" v-if="poll.voters?.length">
          <input
            type="text"
            class="form-control form-control-sm mb-3"
            @keyup="filterSearch"
            v-model="search"
            :placeholder="$t('statistics.search')"
          />
          <small v-if="search" class="mb-3"
            ><b>"{{ search }}"</b> {{ $t("statistics.showingResults") }}</small
          >
          <table class="table border">
            <thead class="thead-default">
              <tr>
                <th class="text-left small">
                  <b>{{ $t("statistics.username") }}</b>
                </th>
                <th class="text-center small">
                  <b>{{ $t("statistics.vote") }}</b>
                </th>
                <th class="text-center small">
                  <b>{{ $t("statistics.votedAt") }}</b>
                </th>
                <!-- <th class="text-center"><?=$lang['statistics']['age']?></th> -->
              </tr>
            </thead>
            <tbody>
              <tr v-for="voter in searchResults" :key="voter.id">
                <td width="40%" class="text-left">
                  <div
                    class="d-flex align-items-center align-self-center align-content-center"
                  >
                    <nuxt-link
                      rel="dofollow"
                      :to="localePath('/profile/' + voter.voter_username)"
                      :title="voter.voter_username"
                    >
                      <img
                        v-if="voter.voter_photo"
                        loading="lazy"
                        class="lazyload img-fluid"
                        :data-src="
                          $config.UPLOADS_URL + '/users/' + voter.voter_photo
                        "
                        :alt="voter.voter_username"
                        width="100"
                      />
                      <i class="fa fa-user-circle fa-2x" v-else></i>
                    </nuxt-link>
                    <nuxt-link
                      rel="dofollow"
                      class="ms-2"
                      :to="localePath('/profile/' + voter.voter_username)"
                      :title="voter.voter_username"
                    >
                      {{ voter.voter_username }}</nuxt-link
                    >
                  </div>
                  <small> {{ voter.city }} - {{ voter.district }} </small>
                </td>
                <td width="40%" class="text-center small align-middle">
                  <b>{{
                    parseInt(poll.polltype) === 2
                      ? $t("poll." + voter.answer)
                      : voter.answer
                  }}</b>
                </td>
                <td
                  width="20%"
                  class="text-center text-nowrap small align-middle"
                >
                  {{ voter.createdAt }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- End Main -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: null,
      statistics: null,
      poll: null,
      searchResults: [],
    };
  },
  mounted() {
    this.getPoll();
    this.$nextTick(() => {
      setTimeout(() => {
        if ($("#question-gender").length) {
          let genders = this.poll.genders.chart;
          let gendersDataLabelss = genders.labels;
          let gendersDataCnts = genders.data;
          let gendersDataTitle = genders.title;
          let gendersDataClrs = genders.colors;

          $.barChart(
            "question-gender",
            gendersDataLabelss,
            gendersDataCnts,
            gendersDataClrs,
            gendersDataTitle,
            "horizontalBar"
          );
        }
        if ($("#question-age").length) {
          let ages = this.poll.ages.chart;
          let agesDataLabelss = ages.labels;
          let agesDataCnts = ages.data;
          let agesDataClrs = ages.colors;
          $.pieChart(
            "question-age",
            agesDataLabelss,
            agesDataCnts,
            agesDataClrs
          );
        }
      }, 500);
    });
  },
  methods: {
    filterSearch() {
      this.searchResults = this.poll.voters.filter((voter) => {
        return (
          voter?.voter_username
            ?.toLowerCase()
            ?.includes(this?.search?.toLowerCase()) ||
          voter?.city?.toLowerCase()?.includes(this.search?.toLowerCase()) ||
          voter?.district
            ?.toLowerCase()
            ?.includes(this.search?.toLowerCase()) ||
          voter?.answer?.toLowerCase()?.includes(this.search?.toLowerCase())
        );
      });
    },
    async getPoll() {
      try {
        const { data } = await this.$axios.get(
          "/panel/poll-statistic/" + this.$route.params.id
        );
        this.poll = data.data;
        this.searchResults = this.poll.voters;
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>