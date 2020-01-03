require('./bootstrap');

import Vue from 'vue'
import axios from 'axios'
import {router} from './_helpers'
import Toasted from 'vue-toasted'
import App from './components/App.vue'

Vue.use( Toasted, {
  position: 'bottom-left',
  duration: '3000'
})

import {store} from './_store'

const app = new Vue({
  router,
  store,
  el: '#app',
  components: { App },
  mounted(){
    console.log('App component mounted');
  }
});
