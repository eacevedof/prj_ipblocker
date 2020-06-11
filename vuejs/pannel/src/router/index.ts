import Vue from 'vue';
import VueRouter, { RouteConfig } from 'vue-router';
import Home from '../views/Home.vue';
Vue.use(VueRouter);

const routes: Array<RouteConfig> = [
  {
    path: '/',
    name: 'home',
    component: Home,
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
    component: () => import(/* webpackChunkName: "about" */ '../views/Login.vue'),
  },
  {
    path: '/ip-request',
    name: 'iprequest',
    meta:{
      title: "IP Requests"
    },    
    component: () => import('../views/IpRequest.vue'),
  },  
  {
    path: '/logout',
    name: 'logout',
    meta:{
      title: "Logout"
    },    
    component: () => import('../views/Logout.vue'),
  },    
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
