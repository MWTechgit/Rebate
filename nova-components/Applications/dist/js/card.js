!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:r})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=0)}([function(t,e,n){n(1),t.exports=n(6)},function(t,e,n){Nova.booting(function(t,e){t.component("applications",n(2))})},function(t,e,n){var r=n(3)(n(4),n(5),!1,null,null,null);t.exports=r.exports},function(t,e){t.exports=function(t,e,n,r,o,s){var i,a=t=t||{},c=typeof t.default;"object"!==c&&"function"!==c||(i=t,a=t.default);var l,u="function"==typeof a?a.options:a;if(e&&(u.render=e.render,u.staticRenderFns=e.staticRenderFns,u._compiled=!0),n&&(u.functional=!0),o&&(u._scopeId=o),s?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(s)},u._ssrRegister=l):r&&(l=r),l){var f=u.functional,d=f?u.render:u.beforeCreate;f?(u._injectStyles=l,u.render=function(t,e){return l.call(e),d(t,e)}):u.beforeCreate=d?[].concat(d,l):[l]}return{esModule:i,exports:a,options:u}}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:["card"],created:function(){console.log("card created")}}},function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("card",{staticClass:"flex flex-col nova-card"},[n("div",{staticClass:"px-6 py-4"},[n("h3",{staticClass:"mb-3 text-90 font-bold text-lg text-center"},[t._v("Applications")]),t._v(" "),n("div",{staticClass:"mb-2 text-blue"},[n("span",[t._v("30")]),t._v(" "),n("a",{staticClass:"text-blue",attrs:{href:"#"}},[t._v("New")])]),t._v(" "),n("div",{staticClass:"mb-2"},[n("span",[t._v("15")]),t._v(" "),n("a",{staticClass:"text-blue",attrs:{href:"#"}},[t._v("Pending review")])])])])},staticRenderFns:[]}},function(t,e){}]);