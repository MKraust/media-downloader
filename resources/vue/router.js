import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: "/",
      redirect: "/dashboard",
      component: () => import("@/view/layout/Layout"),
      children: [
        {
          path: "/dashboard",
          name: "dashboard",
          component: () => import("@/view/pages/Dashboard.vue")
        },
        {
          name: 'tracker',
          path: '/:trackerId',
          component: () => import('@/view/pages/Tracker.vue'),
        },
        {
          name: 'media',
          path: '/:trackerId/:mediaId',
          component: () => import('@/view/pages/Tracker.vue'),
        }
      ]
    },
  ]
});
