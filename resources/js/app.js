/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
import Vue from 'vue'
import store from './store/store'
import TurboLinksAdapter from 'vue-turbolinks'
import VModal from "vue-js-modal";
import TurboLinks from 'turbolinks'
import PortalVue from 'portal-vue'
import InstantSearch from "vue-instantsearch";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import {ObserveVisibility} from "vue-observe-visibility";
import algoliaSearch from "@/mixins/algoliaSearch";

TurboLinks.start()
window.Vue = Vue;
Vue.use(TurboLinksAdapter)
Vue.use(VModal)
Vue.use(PortalVue)
Vue.use(InstantSearch)
Vue.directive("observe-visibility", ObserveVisibility)
window.events = new Vue();
window.flash = function (message, level = "success") {
    window.events.$emit('flash', {message, level})
}
let authorizations = require('./utils/authorizations')
Vue.prototype.authorize = function (...params) {
    if (!window.App.signedIn) return false

    if (typeof params[0] === 'string')
        return authorizations[params[0]](params[1])

    return params[0](window.App.user)
}
Vue.prototype.signedIn = window.App.signedIn
Vue.prototype.$indexname = 'tweets'
Vue.filter('diffForHumans', date => {
    if (!date) return null

    return dayjs(date).fromNow()
})

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
Vue.component('dropdown', require('./components/Dropdown').default)
Vue.component('delete-tweet-modal', require('./utils/DeleteTweetModal').default)
Vue.component('add-reply-modal', require('./utils/AddReplyModal').default)
Vue.component('replies', require('./components/Replies').default)
Vue.component('notification-link', require('./components/NotificationLink').default)
Vue.component('reply-button', require('./components/ReplyButton').default)
Vue.component('delete-reply-modal', require('./utils/DeleteReplyModal').default)
Vue.component('tabs', require('./components/Tabs').default)
Vue.component('tab', require('./components/Tab').default)
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

document.addEventListener('turbolinks:load', () => {
    const app = new Vue({
        created() {
            dayjs.extend(relativeTime)
        },
        mixins: [algoliaSearch],
        store,
        el: '#app',
    });
})
