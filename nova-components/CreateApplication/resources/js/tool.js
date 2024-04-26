Nova.booting((Vue, router, store) => {

    window.Vue = Vue;

	Vue.config.devtools = true;


    // Erin! In 2021-02, You got fed up with npm not compiling across all the versions of webpack in
    // this project and got fed up. Instead you copy and pasted the entire form code into
    // the /src folder

    router.addRoutes([
        {
            name: 'create-application',
            path: '/create-application',
            component: require('./components/Tool').default
        },
    ])
})