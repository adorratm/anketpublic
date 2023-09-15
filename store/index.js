export const state = () => ({
    settings: {},
    menus: [],
    footerMenus: [],
    categories: [],
    pages: [],
    showBreadCrumb: true,
    breadCrumbTitle: null,
    breadCrumbDescription: null,
    showSlider: false,
    counters: {},
});

export const mutations = {
    SET_SETTINGS(state, settings) {
        state.settings = settings;
    },
    SET_MENUS(state, menus) {
        state.menus = menus;
    },
    SET_FOOTER_MENUS(state, footerMenus) {
        state.footerMenus = footerMenus;
    },
    SET_CATEGORIES(state, categories) {
        state.categories = categories;
    },
    SET_BREADCRUMB(state, payload) {
        state.showBreadCrumb = payload.showBreadCrumb;
        state.breadCrumbTitle = payload.breadCrumbTitle ?? null;
        state.breadCrumbDescription = payload?.breadCrumbDescription ?? null;
    },
    SET_SLIDER(state, payload) {
        state.showSlider = payload;
    },
    SET_PAGES(state, pages) {
        state.pages = pages;
    },
    SET_COUNTERS(state, counters) {
        state.counters = counters;
    },
};

export const actions = {
    async nuxtServerInit({ dispatch }) {
        await Promise.all([
            dispatch('getSettings', { id: 1 }),
            dispatch('getMenus'),
            dispatch('getCategories'),
            dispatch('getPages'),
            dispatch('getCounters'),
        ]);
    },
    async getPages({ commit }) {
        try {
            const pages = await this.$axios.$get('pages');
            if (pages.data) {
                commit('SET_PAGES', pages.data);
            }
        } catch (e) {
            console.log(e);
        }

    },
    async getSettings({ commit }, data) {
        try {
            const settings = await this.$axios.$get('settings/' + data.id);
            if (settings.data) {
                commit('SET_SETTINGS', settings.data);
            }
        } catch (e) {
            console.log(e);
        }
    },
    async getMenus({ commit }) {
        try {
            const menus = await this.$axios.$get('menus');
            if (menus.menus) {
                menus.menus = menus.menus.map((element) => {
                    if (element.page_url) {
                        element.url = "/pages/" + element.page_url;
                    }
                    if (element.category_url) {
                        element.url =
                            "/categories/" + element.category_url;
                    }
                    if (element.poll_url) {
                        element.url =
                            "/polls/" + element.poll_url;
                    }
                    return element;
                });
                commit('SET_MENUS', menus.menus);
            }
            if (menus.footer_menus) {
                menus.footer_menus = menus.footer_menus.map((element) => {
                    if (element.page_url) {
                        element.url = "/pages/" + element.page_url;
                    }
                    if (element.category_url) {
                        element.url =
                            "/categories/" + element.category_url;
                    }
                    if (element.poll_url) {
                        element.url =
                            "/polls/" + element.poll_url;
                    }
                    return element;
                });

                commit('SET_FOOTER_MENUS', menus.footer_menus);
            }
        } catch (e) {
            console.log(e);
        }
    },
    async getCategories({ commit }) {
        try {
            const categories = await this.$axios.$get('/categories');
            if (categories.data) {
                commit('SET_CATEGORIES', categories.data);
            }
        } catch (e) {
            console.log(e);
        }
    },
    async getPages({ commit }) {
        try {
            const pages = await this.$axios.$get('pages');
            if (pages.data) {
                commit('SET_PAGES', pages.data);
            }
        } catch (e) {
            console.log(e);
        }
    },
    async getCounters({ commit }) {
        try {
            const counters = await this.$axios.$get('counters');
            if (counters.data) {
                commit('SET_COUNTERS', counters.data);
            }
        } catch (e) {
            console.log(e);
        }
    },
};