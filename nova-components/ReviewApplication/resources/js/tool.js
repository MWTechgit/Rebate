Nova.booting((Vue, router, store) => {
    Vue.config.devtools = true;
    window.Vue = Vue;

    Vue.component('review-application', require('./components/Tool'))
});

Nova.booting((Vue, router, store) => {
    Vue.config.devtools = true;
    window.Vue = Vue;
    
    // Force scroll to top on "page load"
    router.afterEach((to, from) => {
        window.scrollTo(0, 0);
    })
});

// Nova.booting((Vue, router, store) => {
//     router.beforeEach((to, from, next) => {
//         if (false == from.hasOwnProperty('params')) {
//             next();
//         }

//         if (false == from.params.hasOwnProperty('resourceName')) {
//             next();
//         }

//         if (from.params.resourceName == 'owners') {
//             // redirect back to application detail
//         }

//         next()
//     })
// });