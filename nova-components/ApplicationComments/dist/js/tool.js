!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:r})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=1)}([function(t,e){t.exports=function(t,e,n,r,o,i){var s,a=t=t||{},c=typeof t.default;"object"!==c&&"function"!==c||(s=t,a=t.default);var u,d="function"==typeof a?a.options:a;if(e&&(d.render=e.render,d.staticRenderFns=e.staticRenderFns,d._compiled=!0),n&&(d.functional=!0),o&&(d._scopeId=o),i?(u=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),r&&r.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(i)},d._ssrRegister=u):r&&(u=r),u){var l=d.functional,f=l?d.render:d.beforeCreate;l?(d._injectStyles=u,d.render=function(t,e){return u.call(e),f(t,e)}):d.beforeCreate=f?[].concat(f,u):[u]}return{esModule:s,exports:a,options:d}}},function(t,e,n){n(2),t.exports=n(14)},function(t,e,n){Nova.booting(function(t,e,r){t.config.devtools=!0,t.component("application-comments",n(3))})},function(t,e,n){var r=n(0)(n(4),n(13),!1,null,null,null);t.exports=r.exports},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var r=n(5),o=n.n(r);e.default={props:["resourceName","resourceId","field"],components:{Comments:o.a},computed:{endpoint:function(){return"/applications/"+this.resourceId+"/comments"}}}},function(t,e,n){var r=n(0)(n(11),n(12),!1,function(t){n(6)},null,null);t.exports=r.exports},function(t,e,n){var r=n(7);"string"==typeof r&&(r=[[t.i,r,""]]),r.locals&&(t.exports=r.locals);n(9)("2bccbe24",r,!0,{})},function(t,e,n){(t.exports=n(8)(!1)).push([t.i,"ul.comments{list-style-type:none;padding-left:0}.comment{-webkit-box-align:center;-ms-flex-align:center;align-items:center}.comment p{padding:.75rem;color:var(--90)}.comment-meta small{float:right}",""])},function(t,e){t.exports=function(t){var e=[];return e.toString=function(){return this.map(function(e){var n=function(t,e){var n=t[1]||"",r=t[3];if(!r)return n;if(e&&"function"==typeof btoa){var o=(s=r,"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(s))))+" */"),i=r.sources.map(function(t){return"/*# sourceURL="+r.sourceRoot+t+" */"});return[n].concat(i).concat([o]).join("\n")}var s;return[n].join("\n")}(e,t);return e[2]?"@media "+e[2]+"{"+n+"}":n}).join("")},e.i=function(t,n){"string"==typeof t&&(t=[[null,t,""]]);for(var r={},o=0;o<this.length;o++){var i=this[o][0];"number"==typeof i&&(r[i]=!0)}for(o=0;o<t.length;o++){var s=t[o];"number"==typeof s[0]&&r[s[0]]||(n&&!s[2]?s[2]=n:n&&(s[2]="("+s[2]+") and ("+n+")"),e.push(s))}},e}},function(t,e,n){var r="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!r)throw new Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var o=n(10),i={},s=r&&(document.head||document.getElementsByTagName("head")[0]),a=null,c=0,u=!1,d=function(){},l=null,f="data-vue-ssr-id",p="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function m(t){for(var e=0;e<t.length;e++){var n=t[e],r=i[n.id];if(r){r.refs++;for(var o=0;o<r.parts.length;o++)r.parts[o](n.parts[o]);for(;o<n.parts.length;o++)r.parts.push(h(n.parts[o]));r.parts.length>n.parts.length&&(r.parts.length=n.parts.length)}else{var s=[];for(o=0;o<n.parts.length;o++)s.push(h(n.parts[o]));i[n.id]={id:n.id,refs:1,parts:s}}}}function v(){var t=document.createElement("style");return t.type="text/css",s.appendChild(t),t}function h(t){var e,n,r=document.querySelector("style["+f+'~="'+t.id+'"]');if(r){if(u)return d;r.parentNode.removeChild(r)}if(p){var o=c++;r=a||(a=v()),e=b.bind(null,r,o,!1),n=b.bind(null,r,o,!0)}else r=v(),e=function(t,e){var n=e.css,r=e.media,o=e.sourceMap;r&&t.setAttribute("media",r);l.ssrId&&t.setAttribute(f,e.id);o&&(n+="\n/*# sourceURL="+o.sources[0]+" */",n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(o))))+" */");if(t.styleSheet)t.styleSheet.cssText=n;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(n))}}.bind(null,r),n=function(){r.parentNode.removeChild(r)};return e(t),function(r){if(r){if(r.css===t.css&&r.media===t.media&&r.sourceMap===t.sourceMap)return;e(t=r)}else n()}}t.exports=function(t,e,n,r){u=n,l=r||{};var s=o(t,e);return m(s),function(e){for(var n=[],r=0;r<s.length;r++){var a=s[r];(c=i[a.id]).refs--,n.push(c)}e?m(s=o(t,e)):s=[];for(r=0;r<n.length;r++){var c;if(0===(c=n[r]).refs){for(var u=0;u<c.parts.length;u++)c.parts[u]();delete i[c.id]}}}};var g,y=(g=[],function(t,e){return g[t]=e,g.filter(Boolean).join("\n")});function b(t,e,n,r){var o=n?"":r.css;if(t.styleSheet)t.styleSheet.cssText=y(e,o);else{var i=document.createTextNode(o),s=t.childNodes;s[e]&&t.removeChild(s[e]),s.length?t.insertBefore(i,s[e]):t.appendChild(i)}}},function(t,e){t.exports=function(t,e){for(var n=[],r={},o=0;o<e.length;o++){var i=e[o],s=i[0],a={id:t+":"+o,css:i[1],media:i[2],sourceMap:i[3]};r[s]?r[s].parts.push(a):n.push(r[s]={id:s,parts:[a]})}return n}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:["endpoint"],data:function(){return{comments:[],busy:!1,creating:!1,editing:!1,deleting:!1,showForm:!1,modContent:"",apiError:!1}},mounted:function(){this.fetch()},methods:{fetch:function(){var t=this;this.apiError=!1,this.busy=!0,axios.get(this.endpoint).then(function(e){return t.comments=e.data.data}).catch(function(e){return t.fail(e)}).finally(function(){return t.busy=!1})},create:function(){this.editing=!1,this.creating=!0,this.modContent=""},store:function(){var t=this;this.creating=!1,this.busy=!0,axios.post(this.endpoint,{content:this.modContent}).then(function(e){return t.comments.push(e.data)}).catch(function(e){return t.fail(e)}).finally(function(){return t.busy=!1})},edit:function(t){this.creating=!1,this.editing=t,this.modContent=t.content},update:function(){var t=this,e=this.editing.id;this.editing=!1,this.busy=!0,axios.put(this.endpoint+"/"+e,{content:this.modContent}).then(function(e){return t.comments=t.comments.map(function(t){return t.id===e.data.id?e.data:t})}).catch(function(e){return t.fail(e)}).finally(function(){return t.busy=!1})},ddelete:function(t){this.deleting=t},destroy:function(){var t=this,e=this.deleting.id;this.deleting=!1,this.busy=!0,axios.delete(this.endpoint+"/"+e).then(function(n){return t.comments=t.comments.filter(function(t){return t.id!=e})}).catch(function(e){return t.fail(e)}).finally(function(){return t.busy=!1})},fail:function(t){if(console.error(t),"string"==typeof t)var e=t;else if((t||{}).response)e=_.get(t.response,"data.message")||t.response.statusText;else e=t.message||t.name||"Something went wrong. Contact a system admin.";this.apiError=e,this.$toasted.show(e,{type:"error"})}}}},function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"w-full flex items-center mb-6"},[n("heading",{staticClass:"mb-3 text-90 font-normal text-2xl"},[t._v("Comments")]),t._v(" "),n("div",{staticClass:"flex-no-shrink ml-auto"},[n("button",{staticClass:"btn btn-default btn-primary",on:{click:t.create}},[t._v("Add Comment")])])],1),t._v(" "),n("loading-card",{staticClass:"mb-6 py-3 px-6",attrs:{loading:t.busy}},[t.apiError?n("div",{staticClass:"py-4 border-b border-40"},[n("p",[t._v("The following error was received: "),n("span",{staticStyle:{color:"red"}},[t._v(t._s(t.apiError))])]),t._v(" "),n("button",{staticClass:"mt-3 btn btn-default bg-grey-light hover:bg-grey text-grey-darkest",attrs:{type:"button"},on:{click:t.fetch}},[t._v("\n                    Try again?\n                ")])]):t._e(),t._v(" "),t.creating?n("div",{staticClass:"w-full py-4"},[n("textarea",{directives:[{name:"model",rawName:"v-model",value:t.modContent,expression:"modContent"}],staticClass:"w-full form-control form-input form-input-bordered py-3 h-auto",attrs:{rows:"5"},domProps:{value:t.modContent},on:{input:function(e){e.target.composing||(t.modContent=e.target.value)}}}),t._v(" "),n("button",{staticClass:"btn btn-default btn-primary",on:{click:t.store}},[t._v("Save")])]):t._e(),t._v(" "),t.busy?n("loader"):t.comments.length?n("ul",{staticClass:"comments"},t._l(t.comments,function(e){return n("li",{key:e.id,staticClass:"py-4 border-b border-40"},[n("div",{staticClass:"comment flex"},[n("div",{staticClass:"w-1/6 py-4 font-normal text-80"},[e.admin?n("span",[t._v(t._s(e.admin.full_name))]):n("span",[t._v("Unknown")])]),t._v(" "),t.editing&&t.editing.id==e.id?n("div",{staticClass:"w-3/4 py-4"},[n("textarea",{directives:[{name:"model",rawName:"v-model",value:t.modContent,expression:"modContent"}],staticClass:"w-full form-control form-input form-input-bordered py-3 h-auto",attrs:{rows:"3"},domProps:{value:t.modContent},on:{input:function(e){e.target.composing||(t.modContent=e.target.value)}}}),t._v(" "),n("button",{staticClass:"btn btn-default btn-primary",on:{click:t.update}},[t._v("Save")])]):n("p",{staticClass:"w-5/6 py-4 text-90"},[t._v(t._s(e.content))])]),t._v(" "),n("div",{staticClass:"comment-meta"},[n("a",{staticClass:"cursor-pointer text-70 hover:text-primary mr-3",attrs:{href:"javascript:void(0);",title:"Edit"},on:{click:function(n){return t.edit(e)}}},[n("icon",{attrs:{type:"edit"}})],1),t._v(" "),n("a",{staticClass:"cursor-pointer text-70 hover:text-primary mr-3",attrs:{href:"javascript:void(0);",title:"Delete"},on:{click:function(n){return t.ddelete(e)}}},[n("icon",{attrs:{type:"delete"}})],1),t._v(" "),n("small",{staticClass:"text-70"},[t._v(t._s(e.created_at)+" \n                            "),e.updated_at&&e.updated_at!==e.created_at?n("em",{staticClass:"text-muted"},[t._v(" - Edited on "+t._s(e.updated_at))]):t._e()])])])}),0):n("p",[t._v("\n\t\t\t\tThere are no comments\n    \t\t")])],1),t._v(" "),t.deleting?n("delete-resource-modal",{on:{close:function(e){t.deleting=!1},confirm:t.destroy}}):t._e()],1)},staticRenderFns:[]}},function(t,e){t.exports={render:function(){var t=this.$createElement;return(this._self._c||t)("Comments",{attrs:{endpoint:this.endpoint}})},staticRenderFns:[]}},function(t,e){}]);