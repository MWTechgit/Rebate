import Vue from 'vue'
window.Vue = Vue;

Vue.config.devtools = true;

import RebatesApplicationForm from './components/RebatesApplicationForm.vue'
import Toasted from 'vue-toasted';

Vue.use(Toasted, {
	position: 'top-center',
	duration: 4000,
	containerClass: 'alert-list',
	iconPack: 'fontawesome',
});
Vue.component('rebates-application-form', RebatesApplicationForm);

const app = new Vue({
  el: '#app'
});