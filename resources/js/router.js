import VueRouter from 'vue-router';
import store from './store';

// Define route components
const router = new VueRouter( {
  mode: 'history',
  base: window.configPath,
  routes: [
  ],
  scrollBehavior( to, from, savedPosition ) {
    return {x: 0, y: 0};
  },
} );

router.beforeResolve( ( to, from, next ) => {
} );

router.afterEach( ( to, from ) => {
} );

export default router;

