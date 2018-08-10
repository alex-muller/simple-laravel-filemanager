import Vue from 'vue'
import App from './App'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import axios from 'axios'

Vue.prototype.axios = axios
Vue.use(BootstrapVue);

Vue.component('App', App)

const app = new Vue({
  el: '#app'
});
