import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./core/services/store";
import ApiService from "./core/services/api.service";
import MockService from "./core/mock/mock.service";
import { VERIFY_AUTH } from "./core/services/store/auth.module";
import { RESET_LAYOUT_CONFIG } from "@/core/services/store/config.module";

Vue.config.productionTip = false;

// Global 3rd party plugins
import "popper.js";
import "tooltip.js";
import PerfectScrollbar from "perfect-scrollbar";
window.PerfectScrollbar = PerfectScrollbar;
// import ClipboardJS from "clipboard";
// window.ClipboardJS = ClipboardJS;

// Vue 3rd party plugins
import lodash from 'lodash';
import "./core/plugins/portal-vue";
import "./core/plugins/bootstrap-vue";
import "./core/plugins/perfect-scrollbar";
import "./core/plugins/inline-svg";
import "./core/plugins/metronic";
import "@mdi/font/css/materialdesignicons.css";

Vue.prototype.$lodash = lodash;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
