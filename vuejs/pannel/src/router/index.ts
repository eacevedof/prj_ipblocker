import Vue from 'vue';
import VueRouter, { RouteConfig } from 'vue-router';
import home from '../views/home.vue';
Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'home',
    component: home,
    meta:{
      title: "IP Blocker"
    }
  },
  {
    path: '/login',
    name: 'login',
    meta:{
      title: "Login"
    },    
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/login.vue'),
  },
  {
    path: '/ip-request/:page?',
    name: 'iprequest',
    meta:{
      title: "IP Requests"
    },    
    component: () => import('../views/iprequest.vue'),
  },
  {
    path: '/ip-blacklist/:page?',
    name: 'ipblacklist',
    meta:{
      title: "IP Blacklist"
    },    
    component: () => import('../views/ipblacklist.vue'),
  },    
  {
    path: '/logout',
    name: 'logout',
    meta:{
      title: "Logout"
    },    
    component: () => import('../views/logout.vue'),
  },    
  {
    path: '/404',
    name: 'notfound',
    meta:{
      title: "404 Not found"
    },    
    component: () => import('../views/notfound.vue'),
  },  
  { path: '*', redirect: '/404' }
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
});

router.afterEach((to, from) => {
  // Use next tick to handle router history correctly
  // see: https://github.com/vuejs/vue-router/issues/914#issuecomment-384477609
  Vue.nextTick(() => {
      document.title = to.meta.title || "-";
  });
});

export default router;
