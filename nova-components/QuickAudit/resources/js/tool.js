Nova.booting((Vue, router, store) => {

    Vue.config.devtools = true;
    
    Vue.component('quick-audit', require('./components/Tool'))
})
