Nova.booting((Vue, router) => {
    Vue.config.devtools = true;
    Vue.component('claim-actions', require('./components/Tool'));
})
