Nova.booting((Vue, router, store) => {
    Vue.config.devtools = true
    Vue.component('claim-comments', require('./components/Tool'))
})
