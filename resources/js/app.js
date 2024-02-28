import VueRouter from 'vue-router';
import router from './router.js';
import store from './store.js';

window.$ = window.jQuery = require( 'jquery' );
// window._ = require( 'lodash' );
// window.axios = require( 'axios' );

// if ( !_.isUndefined( wpApiSettings.nonce ) && !_.isNull( wpApiSettings.nonce ) ) {
//   window.axios.defaults.headers.common['X-WP-Nonce'] = wpApiSettings.nonce;
// }

window.Vue = require( 'vue' );
Vue.prototype.$log = console.log;
Vue.use( VueRouter );

Vue.directive( 'click-outside', {
  bind: function( el, binding, vnode ) {
    el.clickOutsideEvent = function( event ) {
      if ( !( el == event.target || el.contains( event.target ) ) ) {
        vnode.context[binding.expression]( event );
      }
    };

    document.body.addEventListener( 'click', el.clickOutsideEvent );
  },
  unbind: function( el ) {
    document.body.removeEventListener( 'click', el.clickOutsideEvent );
  },
} );
import Dashboard from './components/Dashboard.vue';
window.addEventListener( 'DOMContentLoaded', () => {
  if ( document.querySelector( '#app' ) ) {
    const app = new Vue( {
      router,
      store,
      el: '#app',
      props: {
      },
      data: function() {
        return {};
      },
      created() {
      },
      methods: {
      },
      components: {
        Dashboard,
      },
    } );
  }
} );

window.toggleGridOverlay = function() {
  const template = `<div id="gridOverlay" class="fixed inset-0 z-50 opacity-25 pointer-events-none">
      <div class="container h-full">
        <div class="row h-full items-stretch">
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
          <div class="w-1/12 col">
            <div class="h-full" style="background-color: #fc8181;"></div>
          </div>
        </div>
      </div>
    </div>`;
  if ( document.getElementById( 'gridOverlay' ) ) {
    document.getElementById( 'gridOverlay' ).remove();
  } else {
    document.body.insertAdjacentHTML( 'beforeend', template );
  }
};
