Nova.booting((Vue, router, store) => {

    Vue.config.devtools = true;
    
    router.addRoutes([
        {
            name: 'fullpage-search',
            path: '/fullpage-search',
            component: require('./components/Tool'),
        },
    ])
})
