
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: '#app'
// });

$(document).ready(function(){
	// Match height for all cards using jquery-match-height. BB 01/27/2018
	$('.match-height').matchHeight();
	// Initialize jQuery datepicker.
	$(".datepicker" ).datepicker({dateFormat:"yy-mm-dd"});
});
