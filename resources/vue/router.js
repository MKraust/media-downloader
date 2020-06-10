import Vue from "vue";
import Router from "vue-router";

import Layout from '@/view/layout/Layout';
import Tracker from '@/view/pages/Tracker';
import Media from '@/view/pages/Media';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: "/",
      component: Layout,
      children: [
        {
          name: 'tracker',
          path: '/:trackerId',
          component: Tracker,
        },
        {
          name: 'media',
          path: '/:trackerId/:mediaId',
          component: Media,
        },
      ],
    },
  ]
});
