export default function ({ $axios, app }) {
    $axios.interceptors.request.use((config) => {
        const currentLocale = app.i18n.locale
        config.baseURL = config.baseURL + '/'
        config.params = {...config.params, timestamp: new Date().getTime()}
        return config
    });
    const api = $axios.create({
        headers: {
            common: {
                Accept: 'application/json'
            },
            'Cache-Control': 'no-cache',
            'Pragma': 'no-cache',
            'Expires': '0',
        },
    });
    api.onRequest((config) => {
        if (app.$auth.loggedIn) {
            const token = app.$auth.strategy.token.get().split(' ')[1];
            api.setToken(token, 'Bearer')
        }
    })
}