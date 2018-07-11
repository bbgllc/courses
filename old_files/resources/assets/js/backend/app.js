//require('../bootstrap');

window.Vue = require('vue');
import { Form, HasError, AlertError, AlertErrors, AlertSuccess } from 'vform'

require('selectize');

import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
Vue.use(VueFormWizard)

// translations
Vue.prototype.trans = (key) => {
    return _.get(window.trans, key, key);
};

// settings
Vue.prototype.settings = (key) => {
    return _.get(window.stg, key, key);
};

import Chartkick from 'chartkick'
import VueChartkick from 'vue-chartkick'
import Chart from 'chart.js'
Vue.use(VueChartkick, { Chartkick })

import VueGoodTable from 'vue-good-table';
Vue.use(VueGoodTable);

import VuejsDialog from "vuejs-dialog"
Vue.use(VuejsDialog)

Vue.component('site-installer', require('./components/Installer.vue'));
Vue.component('transaction-list', require('./components/Transactions.vue'));
Vue.component('course-list', require('./components/CoursesList.vue'));
Vue.component('admin-sales-chart', require('./components/SalesChart.vue'));
Vue.component('upload-logos', require('./components/Logos.vue'));
Vue.component('upload-image', require('./components/ImageUpload.vue'));
Vue.component('env-editor', require('./components/EnvEditor.vue'));
Vue.component('site-settings', require('./components/SiteSettings.vue'));
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)

const app = new Vue({
    el: '#app-body'
});



/*****
 * CONFIGURATION
 */
//Main navigation
$.navigation = $('nav > ul.nav');

$.panelIconOpened = 'icon-arrow-up';
$.panelIconClosed = 'icon-arrow-down';

//Default colours
$.brandPrimary =  '#20a8d8';
$.brandSuccess =  '#4dbd74';
$.brandInfo =     '#63c2de';
$.brandWarning =  '#f8cb00';
$.brandDanger =   '#f86c6b';

$.grayDark =      '#2a2c36';
$.gray =          '#55595c';
$.grayLight =     '#818a91';
$.grayLighter =   '#d1d4d7';
$.grayLightest =  '#f8f9fa';

'use strict';

/****
 * MAIN NAVIGATION
 */
$(document).ready(function($){
    // Dropdown Menu
    $.navigation.on('click', 'a', function(e){

        if ($.ajaxLoad) {
            e.preventDefault();
        }

        if ($(this).hasClass('nav-dropdown-toggle')) {
            $(this).parent().toggleClass('open');
            resizeBroadcast();
        }

    });

    function resizeBroadcast() {

        var timesRun = 0;
        var interval = setInterval(function(){
            timesRun += 1;
            if(timesRun == 5){
                clearInterval(interval);
            }
            window.dispatchEvent(new Event('resize'));
        }, 62.5);
    }

    /* ---------- Main Menu Open/Close, Min/Full ---------- */
    $('.navbar-toggler').click(function(){

        if ($(this).hasClass('sidebar-toggler')) {
            $('body').toggleClass('sidebar-hidden');
            resizeBroadcast();
        }

        if ($(this).hasClass('sidebar-minimizer')) {
            $('body').toggleClass('sidebar-minimized');
            resizeBroadcast();
        }

        if ($(this).hasClass('aside-menu-toggler')) {
            $('body').toggleClass('aside-menu-hidden');
            resizeBroadcast();
        }

        if ($(this).hasClass('mobile-sidebar-toggler')) {
            $('body').toggleClass('sidebar-mobile-show');
            resizeBroadcast();
        }

    });

    $('.sidebar-close').click(function(){
        $('body').toggleClass('sidebar-opened').parent().toggleClass('sidebar-opened');
    });

    /* ---------- Disable moving to top ---------- */
    $('a[href="#"][data-top!=true]').click(function(e){
        e.preventDefault();
    });

});

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function init(url) {

    /* ---------- Tooltip ---------- */
    $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});

    /* ---------- Popover ---------- */
    $('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();

}


