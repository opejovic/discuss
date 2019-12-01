/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Toasted from 'vue-toasted';

window.events = new Vue();

window.flash = function (message) {
    window.events.$emit('flash', message)
};

Vue.prototype.auth = window.auth; // ...authenticated user

Vue.filter('pluralize', (word, amount) =>
    amount > 1 || amount == 0 ? `${word}s` : word
);


Vue.use(Toasted,  {
    theme: "toasted-primary",
    position: "bottom-right",
    duration: 5000,
    action: {
        text: 'Close',
        onClick: (e, toastObject) => {
            toastObject.goAway(0);
        }
    },
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('navigation-bar', require('./components/NavigationBar.vue').default);
Vue.component('like-button', require('./components/LikeButton.vue').default);
Vue.component('subscribe-button', require('./components/SubscribeButton.vue').default);
Vue.component('flash-message', require('./components/FlashMessage.vue').default);
Vue.component('replies', require('./components/Replies.vue').default);
Vue.component('paginator', require('./components/Paginator.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
