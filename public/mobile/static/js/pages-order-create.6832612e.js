(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-create"],{"0c2e":function(t,e,r){"use strict";r.r(e);var n=r("7ce9"),i=r.n(n);for(var o in n)"default"!==o&&function(t){r.d(e,t,function(){return n[t]})}(o);e["default"]=i.a},"0c5e":function(t,e,r){"use strict";var n,i=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-uni-view",{staticClass:"content"},[r("v-uni-view",{staticClass:"receipt"},[r("v-uni-view",{staticClass:"left"},[r("v-uni-text",{staticClass:"icon map-marker"})],1),t.addressInfo?r("v-uni-view",{staticClass:"right",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.checkAddress()}}},[r("v-uni-view",{staticClass:"contact"},[t._v("收货人："+t._s(t.addressInfo.consignee)+" "+t._s(t.addressInfo.phone))]),r("v-uni-view",{staticClass:"address"},[t._v("收货地址："+t._s(t.addressInfo.province_name)+" "+t._s(t.addressInfo.city_name)+" "+t._s(t.addressInfo.town_name)+" "+t._s(t.addressInfo.address))])],1):r("v-uni-view",{staticClass:"check",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.checkAddress()}}},[r("v-uni-view",[t._v("选择收货地址")])],1),r("v-uni-view",{staticClass:"icon angle-right"})],1),r("v-uni-view",{staticClass:"receipt-bottom"}),r("v-uni-view",{staticClass:"product-view"},[t._l(t.orderInfo.goods,function(e,n){return r("v-uni-navigator",{key:n,staticClass:"product-item",on:{click:function(r){arguments[0]=r=t.$handleEvent(r),t.goGoodsDetail(e)}}},[r("v-uni-view",{staticClass:"product-image"},[r("img",{attrs:{src:e.pics[0]}})]),r("v-uni-view",{staticClass:"product-info"},[r("v-uni-view",{staticClass:"product-title"},[r("v-uni-text",{staticClass:"title"},[t._v(t._s(e.title))]),r("v-uni-text",{staticClass:"values"},[t._v(t._s(e.values))])],1),r("v-uni-view",{staticStyle:{display:"flex","justify-content":"space-between"}},[r("v-uni-view",{staticClass:"price",staticStyle:{"min-width":"250upx"}},[t._v("¥ "+t._s(e.price))]),r("v-uni-view",{staticClass:"num",staticStyle:{"min-width":"80upx"}},[t._v("x "+t._s(e.num))])],1)],1)],1)}),r("v-uni-view",{staticClass:"order-info"},[r("v-uni-view",[t._v("运费")]),r("v-uni-view",[t._v("¥ "+t._s(t.orderInfo.logistics))])],1),r("v-uni-view",{staticClass:"order-info"},[r("v-uni-view",[t._v("优惠券")]),r("v-uni-view",[t._v("¥ 0.00")])],1)],2),r("v-uni-view",{staticClass:"footer"},[r("v-uni-view",{staticClass:"price",staticStyle:{width:"100%"}},[t._v("合计："),r("v-uni-text",{staticStyle:{color:"#F84C44"}},[t._v("¥ "+t._s(t.orderInfo.sum))])],1),r("v-uni-view",{staticClass:"submit",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.submitOrder.apply(void 0,arguments)}}},[t._v("提交订单")])],1)],1)},o=[];r.d(e,"b",function(){return i}),r.d(e,"c",function(){return o}),r.d(e,"a",function(){return n})},"39da":function(t,e,r){"use strict";var n=r("8cb6"),i=r.n(n);i.a},"3b8d":function(t,e,r){"use strict";r.r(e),r.d(e,"default",function(){return a});var n=r("795b"),i=r.n(n);function o(t,e,r,n,o,a,s){try{var c=t[a](s),u=c.value}catch(d){return void r(d)}c.done?e(u):i.a.resolve(u).then(n,o)}function a(t){return function(){var e=this,r=arguments;return new i.a(function(n,i){var a=t.apply(e,r);function s(t){o(a,n,i,s,c,"next",t)}function c(t){o(a,n,i,s,c,"throw",t)}s(void 0)})}}},"7ce9":function(t,e,r){"use strict";var n=r("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,r("c5f6");var i=n(r("a4bb")),o=n(r("f499"));r("96cf");var a=n(r("3b8d")),s={data:function(){return{ordersData:{},addressInfo:{},orderInfo:{}}},onLoad:function(t){this.ordersData=t.data,this.orderCreate(),this.defaultAddress()},onShow:function(){this.addressInfo=JSON.parse(uni.getStorageSync("address"))},methods:{goGoodsDetail:function(t){console.log(t),uni.navigateTo({url:"/pages/goods/index?gid="+t.id})},defaultAddress:function(){var t=(0,a.default)(regeneratorRuntime.mark(function t(){return regeneratorRuntime.wrap(function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,this.$api("GET","address/default");case 2:this.addressInfo=t.sent,uni.setStorageSync("address",(0,o.default)(this.addressInfo));case 4:case"end":return t.stop()}},t,this)}));function e(){return t.apply(this,arguments)}return e}(),orderCreate:function(){var t=(0,a.default)(regeneratorRuntime.mark(function t(){return regeneratorRuntime.wrap(function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,this.$api("GET","orders/create",{data:this.ordersData});case 2:this.orderInfo=t.sent;case 3:case"end":return t.stop()}},t,this)}));function e(){return t.apply(this,arguments)}return e}(),checkAddress:function(){uni.navigateTo({url:"/pages/address/index?path=create"})},submitOrder:function(){var t=(0,a.default)(regeneratorRuntime.mark(function t(){var e;return regeneratorRuntime.wrap(function(t){while(1)switch(t.prev=t.next){case 0:if("string"!=typeof this.addressInfo&&0!=(0,i.default)(this.addressInfo).length){t.next=3;break}return uni.showToast({title:"请选择收货地址!",duration:2e3,icon:"none"}),t.abrupt("return");case 3:return t.next=5,this.$api("POST","orders",{data:this.ordersData,address:this.addressInfo.id},!0);case 5:e=t.sent,console.log("res_order"),console.log(e),Number(e.data)>0?uni.navigateTo({url:"/pages/order/pay?id="+e.data}):uni.showToast({title:e.msg,duration:2e3});case 9:case"end":return t.stop()}},t,this)}));function e(){return t.apply(this,arguments)}return e}()}};e.default=s},"87b7":function(t,e,r){e=t.exports=r("2350")(!1),e.push([t.i,".receipt[data-v-3552b03a]{font-size:%?28?%;text-align:left;background-color:#fff;border-bottom:%?1?% solid #eee;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.receipt .left[data-v-3552b03a]{margin:%?10?% %?10?% %?10?% %?20?%}.receipt .left .icon[data-v-3552b03a]{font-size:%?46?%;color:#888;-webkit-text-fill-color:transparent;-webkit-text-stroke:1px #000}.receipt .right[data-v-3552b03a]{width:100%;margin:%?10?%}.receipt .right .contact[data-v-3552b03a]{font-size:%?28?%;line-height:%?36?%;padding:%?10?%}.receipt .right .address[data-v-3552b03a]{font-size:%?24?%;line-height:%?32?%;padding:%?10?%;overflow:hidden;text-overflow:ellipsis;-webkit-line-clamp:2;box-orient:vertical}.receipt .check[data-v-3552b03a]{width:100%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;line-height:%?48?%;padding:%?10?%}.receipt .angle-right[data-v-3552b03a]{font-size:%?36?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.receipt-bottom[data-v-3552b03a]{border-top:%?1?% solid #eee;height:%?15?%;background-image:-webkit-repeating-linear-gradient(330deg,#c96f7c,#c96f7c 20px,#fff 0,#fff 30px,#5c85a4 0,#5c85a4 50px,#fff 0,#fff 60px);background-image:repeating-linear-gradient(120deg,#c96f7c,#c96f7c 20px,#fff 0,#fff 30px,#5c85a4 0,#5c85a4 50px,#fff 0,#fff 60px)}.order-info[data-v-3552b03a]{padding:%?30?%;text-align:left;border-bottom:%?1?% solid #eee;background-color:#fff;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.product-view[data-v-3552b03a]{background-color:#fff;margin-bottom:%?100?%}.product-item[data-v-3552b03a]{height:%?200?%;display:-webkit-box;display:-webkit-flex;display:flex;border-bottom:%?1?% solid #ddd}.product-image[data-v-3552b03a]{padding:%?10?% 0 %?10?% 0;width:%?200?%}.product-image img[data-v-3552b03a]{border-radius:%?10?%;border:%?1?% solid #eee;width:%?180?%;height:%?180?%}.product-info[data-v-3552b03a]{padding:%?10?%;-webkit-box-flex:1;-webkit-flex:1;flex:1;display:block}.product-title[data-v-3552b03a]{padding:1%;height:60%;text-align:left}.product-title .title[data-v-3552b03a]{display:block;line-height:%?35?%;max-height:%?70?%;overflow:hidden}.product-title .values[data-v-3552b03a]{line-height:%?50?%;color:#666;font-size:%?22?%}.product-info .price[data-v-3552b03a]{padding:1%;line-height:%?50?%;font-size:%?42?%;color:#f84c44}.product-info .num[data-v-3552b03a]{padding:1%;line-height:%?60?%;font-size:%?28?%}.footer[data-v-3552b03a]{border-top:%?1?% solid #ddd;font-size:%?32?%;height:%?100?%;line-height:%?100?%;display:-webkit-box;display:-webkit-flex;display:flex;position:fixed;bottom:0;left:0;right:0;z-index:99;background-color:#fdfdfd}.footer .submit[data-v-3552b03a]{width:%?400?%;background-color:red;color:#fff}.footer .price[data-v-3552b03a]{width:50%;padding:1%;line-height:%?80?%;text-align:left}",""])},"8cb6":function(t,e,r){var n=r("87b7");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=r("4f06").default;i("0d6c2e1c",n,!0,{sourceMap:!1,shadowMode:!1})},"96cf":function(t,e){!function(e){"use strict";var r,n=Object.prototype,i=n.hasOwnProperty,o="function"===typeof Symbol?Symbol:{},a=o.iterator||"@@iterator",s=o.asyncIterator||"@@asyncIterator",c=o.toStringTag||"@@toStringTag",u="object"===typeof t,d=e.regeneratorRuntime;if(d)u&&(t.exports=d);else{d=e.regeneratorRuntime=u?t.exports:{},d.wrap=m;var f="suspendedStart",l="suspendedYield",h="executing",p="completed",v={},g={};g[a]=function(){return this};var w=Object.getPrototypeOf,b=w&&w(w(G([])));b&&b!==n&&i.call(b,a)&&(g=b);var y=E.prototype=k.prototype=Object.create(g);_.prototype=y.constructor=E,E.constructor=_,E[c]=_.displayName="GeneratorFunction",d.isGeneratorFunction=function(t){var e="function"===typeof t&&t.constructor;return!!e&&(e===_||"GeneratorFunction"===(e.displayName||e.name))},d.mark=function(t){return Object.setPrototypeOf?Object.setPrototypeOf(t,E):(t.__proto__=E,c in t||(t[c]="GeneratorFunction")),t.prototype=Object.create(y),t},d.awrap=function(t){return{__await:t}},L(C.prototype),C.prototype[s]=function(){return this},d.AsyncIterator=C,d.async=function(t,e,r,n){var i=new C(m(t,e,r,n));return d.isGeneratorFunction(e)?i:i.next().then(function(t){return t.done?t.value:i.next()})},L(y),y[c]="Generator",y[a]=function(){return this},y.toString=function(){return"[object Generator]"},d.keys=function(t){var e=[];for(var r in t)e.push(r);return e.reverse(),function r(){while(e.length){var n=e.pop();if(n in t)return r.value=n,r.done=!1,r}return r.done=!0,r}},d.values=G,T.prototype={constructor:T,reset:function(t){if(this.prev=0,this.next=0,this.sent=this._sent=r,this.done=!1,this.delegate=null,this.method="next",this.arg=r,this.tryEntries.forEach(O),!t)for(var e in this)"t"===e.charAt(0)&&i.call(this,e)&&!isNaN(+e.slice(1))&&(this[e]=r)},stop:function(){this.done=!0;var t=this.tryEntries[0],e=t.completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(t){if(this.done)throw t;var e=this;function n(n,i){return s.type="throw",s.arg=t,e.next=n,i&&(e.method="next",e.arg=r),!!i}for(var o=this.tryEntries.length-1;o>=0;--o){var a=this.tryEntries[o],s=a.completion;if("root"===a.tryLoc)return n("end");if(a.tryLoc<=this.prev){var c=i.call(a,"catchLoc"),u=i.call(a,"finallyLoc");if(c&&u){if(this.prev<a.catchLoc)return n(a.catchLoc,!0);if(this.prev<a.finallyLoc)return n(a.finallyLoc)}else if(c){if(this.prev<a.catchLoc)return n(a.catchLoc,!0)}else{if(!u)throw new Error("try statement without catch or finally");if(this.prev<a.finallyLoc)return n(a.finallyLoc)}}}},abrupt:function(t,e){for(var r=this.tryEntries.length-1;r>=0;--r){var n=this.tryEntries[r];if(n.tryLoc<=this.prev&&i.call(n,"finallyLoc")&&this.prev<n.finallyLoc){var o=n;break}}o&&("break"===t||"continue"===t)&&o.tryLoc<=e&&e<=o.finallyLoc&&(o=null);var a=o?o.completion:{};return a.type=t,a.arg=e,o?(this.method="next",this.next=o.finallyLoc,v):this.complete(a)},complete:function(t,e){if("throw"===t.type)throw t.arg;return"break"===t.type||"continue"===t.type?this.next=t.arg:"return"===t.type?(this.rval=this.arg=t.arg,this.method="return",this.next="end"):"normal"===t.type&&e&&(this.next=e),v},finish:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.finallyLoc===t)return this.complete(r.completion,r.afterLoc),O(r),v}},catch:function(t){for(var e=this.tryEntries.length-1;e>=0;--e){var r=this.tryEntries[e];if(r.tryLoc===t){var n=r.completion;if("throw"===n.type){var i=n.arg;O(r)}return i}}throw new Error("illegal catch attempt")},delegateYield:function(t,e,n){return this.delegate={iterator:G(t),resultName:e,nextLoc:n},"next"===this.method&&(this.arg=r),v}}}function m(t,e,r,n){var i=e&&e.prototype instanceof k?e:k,o=Object.create(i.prototype),a=new T(n||[]);return o._invoke=j(t,r,a),o}function x(t,e,r){try{return{type:"normal",arg:t.call(e,r)}}catch(n){return{type:"throw",arg:n}}}function k(){}function _(){}function E(){}function L(t){["next","throw","return"].forEach(function(e){t[e]=function(t){return this._invoke(e,t)}})}function C(t){function e(r,n,o,a){var s=x(t[r],t,n);if("throw"!==s.type){var c=s.arg,u=c.value;return u&&"object"===typeof u&&i.call(u,"__await")?Promise.resolve(u.__await).then(function(t){e("next",t,o,a)},function(t){e("throw",t,o,a)}):Promise.resolve(u).then(function(t){c.value=t,o(c)},function(t){return e("throw",t,o,a)})}a(s.arg)}var r;function n(t,n){function i(){return new Promise(function(r,i){e(t,n,r,i)})}return r=r?r.then(i,i):i()}this._invoke=n}function j(t,e,r){var n=f;return function(i,o){if(n===h)throw new Error("Generator is already running");if(n===p){if("throw"===i)throw o;return z()}r.method=i,r.arg=o;while(1){var a=r.delegate;if(a){var s=I(a,r);if(s){if(s===v)continue;return s}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if(n===f)throw n=p,r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n=h;var c=x(t,e,r);if("normal"===c.type){if(n=r.done?p:l,c.arg===v)continue;return{value:c.arg,done:r.done}}"throw"===c.type&&(n=p,r.method="throw",r.arg=c.arg)}}}function I(t,e){var n=t.iterator[e.method];if(n===r){if(e.delegate=null,"throw"===e.method){if(t.iterator.return&&(e.method="return",e.arg=r,I(t,e),"throw"===e.method))return v;e.method="throw",e.arg=new TypeError("The iterator does not provide a 'throw' method")}return v}var i=x(n,t.iterator,e.arg);if("throw"===i.type)return e.method="throw",e.arg=i.arg,e.delegate=null,v;var o=i.arg;return o?o.done?(e[t.resultName]=o.value,e.next=t.nextLoc,"return"!==e.method&&(e.method="next",e.arg=r),e.delegate=null,v):o:(e.method="throw",e.arg=new TypeError("iterator result is not an object"),e.delegate=null,v)}function S(t){var e={tryLoc:t[0]};1 in t&&(e.catchLoc=t[1]),2 in t&&(e.finallyLoc=t[2],e.afterLoc=t[3]),this.tryEntries.push(e)}function O(t){var e=t.completion||{};e.type="normal",delete e.arg,t.completion=e}function T(t){this.tryEntries=[{tryLoc:"root"}],t.forEach(S,this),this.reset(!0)}function G(t){if(t){var e=t[a];if(e)return e.call(t);if("function"===typeof t.next)return t;if(!isNaN(t.length)){var n=-1,o=function e(){while(++n<t.length)if(i.call(t,n))return e.value=t[n],e.done=!1,e;return e.value=r,e.done=!0,e};return o.next=o}}return{next:z}}function z(){return{value:r,done:!0}}}(function(){return this||"object"===typeof self&&self}()||Function("return this")())},"9ae6":function(t,e,r){"use strict";r.r(e);var n=r("0c5e"),i=r("0c2e");for(var o in i)"default"!==o&&function(t){r.d(e,t,function(){return i[t]})}(o);r("39da");var a,s=r("f0c5"),c=Object(s["a"])(i["default"],n["b"],n["c"],!1,null,"3552b03a",null,!1,n["a"],a);e["default"]=c.exports}}]);