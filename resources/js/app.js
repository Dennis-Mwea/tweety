/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap')
import Vue from 'vue'
import store from './store/store'
import TurboLinksAdapter from 'vue-turbolinks'

window.Vue = Vue;
Vue.use(TurboLinksAdapter)
window.events = new Vue();
window.flash = function (message, level = "success") {
    window.events.$emit('flash', {message, level})
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component('avatar-form', require('./components/AvatarForm').default)
Vue.component('like-buttons', require('./components/LikeButtons').default)
Vue.component('flash', require('./components/Flash').default)
Vue.component('follow-button', require('./components/FollowButton').default)
Vue.component('friends-list', require('./components/FriendsList').default)
Vue.component('banner-form', require('./components/BannerForm').default)
Vue.component('publish-tweet-panel', require('./components/PublishTweetPanel').default)
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

document.addEventListener('turbolinks:load', () => {
    const app = new Vue({
        store,
        el: '#app',
    });
})
