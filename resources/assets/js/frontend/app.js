
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');
require('../plugins');
require('selectize');

window.Vue = require('vue');

import { Form, HasError, AlertError, AlertErrors, AlertSuccess } from 'vform'
import swal from 'sweetalert'

// import into project
import Vue from "vue"
import VuejsDialog from "vuejs-dialog"
Vue.use(VuejsDialog)


Vue.filter('twoDigits', (value) => {
    if ( value.toString().length <= 1 ) {
        return '0'+value.toString()
    }
    return value.toString()
})



var VueFormWizard = require('vue-form-wizard');
Vue.use(VueFormWizard);

var CardJS = require('card-js');
Vue.use(CardJS);

import VueClip from 'vue-clip';
Vue.use(VueClip);

import Vue2Filters from 'vue2-filters';
Vue.use(Vue2Filters);

import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)


import VueToastr from '@deveodk/vue-toastr'
Vue.use(VueToastr, {
    defaultPosition: 'toast-top-right',
    defaultType: 'info',
    defaultTimeout: 2000
})


import Chartkick from 'chartkick'
import VueChartkick from 'vue-chartkick'
import Chart from 'chart.js'
Vue.use(VueChartkick, { Chartkick })


import VueGoodTable from 'vue-good-table';
Vue.use(VueGoodTable);

// translations
Vue.prototype.trans = (key) => {
    return _.get(window.trans, key, key);
};


// settings
Vue.prototype.settings = (key) => {
    return _.get(window.stg, key, key);
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component('affiliate-link', require('./components/affiliate-link.vue'));
Vue.component('home', require('./components/home.vue'));
Vue.component('course', require('./components/course.vue'));

Vue.component('verify-certificate', require('./components/verify-certificate.vue'));
Vue.component('countdown-timer', require('./components/countdown-timer.vue'));
Vue.component('user-sales-chart', require('./components/user/user-sales-chart.vue'));

Vue.component('user-quiz-question', require('./components/user/quiz/question.vue'));
Vue.component('user-quiz', require('./components/user/quiz/quiz.vue'));

Vue.component('course-questions', require('./components/user/questions.vue'));
Vue.component('question-follow', require('./components/user/questions-follow.vue'));
Vue.component('question-comments', require('./components/user/question-comments.vue'));
Vue.component('announcement-comments', require('./components/user/announcement-comments.vue'));
Vue.component('announcements', require('./components/user/announcements.vue'));
Vue.component('course-header', require('./components/course-header.vue'));

Vue.component('loader', require('./components/loader.vue'));
Vue.component('inbox', require('./components/user/inbox.vue'));

Vue.component('author-create-announcement', require('./components/author/announcement-create.vue'));
Vue.component('author-create-course', require('./components/author/course-create.vue'));
Vue.component('author-edit-course', require('./components/author/course-edit.vue'));

Vue.component('course-bookmark', require('./components/user/bookmark.vue'));
Vue.component('mark-as-complete', require('./components/user/mark-as-complete.vue'));
Vue.component('percent-section-completed', require('./components/user/percent-section-completed.vue'));
Vue.component('course-content', require('./components/user/course-content.vue'));

Vue.component('user-profile-edit', require('./components/user/user-profile-edit.vue'));
Vue.component('user-security', require('./components/user/user-security.vue'));
Vue.component('user-settings', require('./components/user/user-settings.vue'));

/// api components to create courses
Vue.component('course-sections', require('./components/author/sections.vue'));

Vue.component('author-courses', require('./components/author/courses.vue'));
Vue.component('course-lessons', require('./components/author/lessons.vue'));
Vue.component('course-lesson', require('./components/author/lesson.vue'));
Vue.component('content-article', require('./components/author/content-article.vue'));
Vue.component('content-video', require('./components/author/content-video.vue'));
Vue.component('content-embed', require('./components/author/content-embed.vue'));
Vue.component('video-uploader', require('./components/author/video-uploader.vue'));
Vue.component('file-attachment', require('./components/author/file-attachment.vue'));

Vue.component('quiz-create', require('./components/author/quiz-create.vue'));
Vue.component('quiz-question', require('./components/author/quiz-question.vue'));
Vue.component('quiz-answer-form', require('./components/author/quiz-answer-form.vue'));
Vue.component('author-coupons', require('./components/author/coupon.vue'));

Vue.component('stars', require('./components/reviews/stars.vue'));
Vue.component('course-reviews', require('./components/reviews/course-reviews.vue'));
Vue.component('course-review', require('./components/reviews/course-review.vue'));
Vue.component('course-review-form', require('./components/reviews/course-review-form.vue'));


Vue.component('cart-checkout-form', require('./components/payments/checkout.vue'));
Vue.component('razorpay-form', require('./components/payments/razorpay.vue'));

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)


import store from '../store';
const app = new Vue({
    el: '#app',
    store: store
});
