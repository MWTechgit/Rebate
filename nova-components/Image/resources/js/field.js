Nova.booting((Vue, router, store) => {
  Vue.component('index-image', require('./components/IndexField'))
  Vue.component('detail-image', require('./components/DetailField'))
  Vue.component('form-image', require('./components/FormField'))
})
