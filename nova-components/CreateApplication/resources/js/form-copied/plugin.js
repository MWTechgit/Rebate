import RebatesApplicationForm from './components/RebatesApplicationForm.vue'
import Toasted         from 'vue-toasted';

const VueRebatesApplicationForm = {
	install(Vue, options) {
		console.log('Installing rebates application.');
		Vue.use(Toasted, {
			position: 'top-center',
			duration: 4000,
			containerClass: 'alert-list',
			iconPack: 'fontawesome',
		});
		Vue.component('rebates-application-form', RebatesApplicationForm);
	}
}

// Automatic installation if Vue has been added to the global scope.
if (typeof window !== 'undefined' && window.Vue) {
	window.Vue.use(VueRebatesApplicationForm)
} else if (typeof Vue !== 'undefined' && Vue) {
	Vue.use(VueRebatesApplicationForm)
}

export default VueRebatesApplicationForm;
export {RebatesApplicationForm};