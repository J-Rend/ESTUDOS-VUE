// =========================================================
// * Vue Material Dashboard - v1.3.2
// =========================================================
//
// * Product Page: https://www.creative-tim.com/product/vue-material-dashboard
// * Copyright 2019 Creative Tim (https://www.creative-tim.com)
// * Licensed under MIT (https://github.com/creativetimofficial/vue-material-dashboard/blob/master/LICENSE.md)
//
// * Coded by Creative Tim
//
// =========================================================
//
// * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from "vue";
import VueRouter from "vue-router";
import App from "./App";
import $ from 'jquery';


window.$ = window.jQuery = $;
// router setup
import routes from "./routes/routes";

// Plugins
import GlobalComponents from "./globalComponents";
import GlobalDirectives from "./globalDirectives";
import Notifications from "./components/NotificationPlugin";

// MaterialDashboard plugin
import MaterialDashboard from "./material-dashboard";

import Chartist from "chartist";
//import styles from './assets/css/dataTables.material.min.css';
//import stylesDt from './assets/css/jquery.dataTables.min.css';


import VueMask from 'v-mask'
Vue.use(VueMask);

import { store } from "./store/index.js";
window.URL_API = process.env.VUE_APP_URL_API;

//import VueMask from 'v-mask'
//Vue.use(VueMask);

// configure router
const router = new VueRouter({
  routes, // short for routes: routes
  linkExactActiveClass: "nav-item active"
});

Vue.prototype.$Chartist = Chartist;

Vue.use(VueRouter);
Vue.use(MaterialDashboard);
Vue.use(GlobalComponents);
Vue.use(GlobalDirectives);
Vue.use(Notifications);

/* eslint-disable no-new */
console.log("store? ", store );
new Vue({
  el: "#app",
  store,
  render: h => h(App),
  router,
  data: {
    Chartist: Chartist
  }
});
