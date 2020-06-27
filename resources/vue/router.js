import Vue from 'vue';
import Router from 'vue-router';

import Layout from '@/view/layout/Layout';
import Tracker from '@/view/pages/Tracker/App';
import Media from '@/view/pages/Media/App';
import Dashboard from '@/view/pages/DashboardNew/App';
import Downloads from '@/view/pages/Downloads/App';
import Favorites from '@/view/pages/Favorites/App';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
    {
      path: "/",
      component: Layout,
      redirect: '/dashboard',
      children: [
        {
          name: 'dashboard',
          path: '/dashboard',
          component: Dashboard,
        },
        {
          name: 'favorites',
          path: '/favorites',
          component: Favorites,
        },
        {
          name: 'downloads',
          path: '/downloads',
          component: Downloads,
        },
        {
          name: 'tracker',
          path: '/:trackerId',
          component: Tracker,
        },
        {
          name: 'media',
          path: '/:trackerId/:mediaId',
          component: Media,
        }
      ],
    },
  ]
});
