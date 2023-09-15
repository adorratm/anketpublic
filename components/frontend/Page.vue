<template>
    <div class="pl-main">
        <div class="pl-box">
            <div class="pl-cover position-relative" v-if="page?.image">
                <img loading="lazy" class="lazyload img-fluid" :data-src="$config.UPLOADS_URL + '/pages/' + page.image" :alt="page.title" />
            </div>
        </div>
        <div class="pl-box p-4" v-if="page?.title">
            <h3 class="text-center">{{ page.title }}</h3>
            <div v-html="page.description"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        seourl: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            page: null,
        };
    },
    mounted() {
        this.getPage(this.seourl);
    },
    methods: {
        async getPage($seo_url = null) {
            try {
                const { data } = await this.$axios.get("/page/" + $seo_url);
                this.page = data.data;
                this.$store.commit("SET_BREADCRUMB", {
                    showBreadCrumb: true,
                    breadCrumbTitle: this.page.title,
                    breadCrumbDescription: this.page.title,
                });
            } catch (error) {
                console.log(error);
            }
        }
    },
    head(){
    return {
      title: this.page?.title,
    }
  }
};
</script>
