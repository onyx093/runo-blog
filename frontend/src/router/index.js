import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import { useUserStore } from '@/stores/user.js';
import { useUxStore } from '@/stores/ux.js';

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
      path: '/articles/create',
      name: 'articles.create',
      component: () => import('../views/ArticleCreate.vue'),
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: '/articles/:id/edit',
      name: 'articles.edit',
      component: () => import('../views/ArticleEdit.vue'),
      meta: {
        requiresAuth: true,
      },
    },
    {
      path: '/users/:id',
      name: 'users.show',
      component: () => import('../views/profile/ShowView.vue'),
      beforeEnter: (to, from) => {
        const userStore = useUserStore();
        if (to.params.id == userStore.user?.id) {
          router.push({ name: 'profile.index' });
        }
      },
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
  const uxStore = useUxStore();

  if (localStorage.getItem('token') && userStore.isLoggedIn === false) {
    uxStore.setIsLoading(true);
    await userStore.loginUser();
    uxStore.setIsLoading(false);
  }

  if (to.meta.requiresAuth && userStore.isLoggedIn === false) {
    next({ name: 'home' });
  }

  next();
});

export default router;
