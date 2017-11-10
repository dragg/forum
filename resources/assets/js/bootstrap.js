window.$ = window.jQuery = require('jquery');

require('bootstrap-sass');

window.axios = require('axios');

window.axios.defaults.headers.common = {
  'Accept': 'application/json',
  'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
  'X-Requested-With': 'XMLHttpRequest',
};

window.events = new Vue();

window.flash = function (message) {
  window.events.$emit('flash', message);
};
