window.Vue = require('vue');
window.Vue.prototype.authorize = function (handler) {
  let user = window.App.user;

  return user ? handler(user) : false;
};

require('./bootstrap');

Vue.component('flash', require('./components/Flash.vue'));
Vue.component('paginator', require('./components/Paginator.vue'));

Vue.component('thread', require('./pages/Thread.vue'));

const app = new Vue({
  el: '#app'
});
