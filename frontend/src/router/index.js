import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import { useUserStore } from '@/stores/user';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/articles',
      name: 'articles.index',
      component: () => import('../views/ArticleIndex.vue'),
    },
    {
      path: '/article/:id',
      name: 'article.show',
      component: () => import('../views/ArticleShow.vue'),
    },
    {
      path: '/my-profile',
      name: 'profile.index',
      component: () => import('../views/profile/IndexView.vue'),
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: '/my-profile/edit',
      name: 'profile.edit',
      component: () => import('../views/profile/EditView.vue'),
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    return {
      top: 0,
      behavior: 'smooth',
    };
  },
});

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();

  if (localStorage.getItem('token') && userStore.isGuest) {
    await userStore.loginUser();
  }

  if (to.meta.requiresAuth && userStore.isGuest) {
    next({ name: 'home' });
  }

  next();
});

export default router;
