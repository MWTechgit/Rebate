
Nova.booting((Vue, router, store) => {
	Vue.config.devtools = true;
    Vue.component('application-comments', require('./components/Tool'))
})
