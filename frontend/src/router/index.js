import { createRouter, createWebHistory } from 'vue-router';
import LoginPage from '../views/LoginPage.vue';
import RegisterPage from '../views/RegisterPage.vue';
import DashboardPage from '../views/DashboardPage.vue';

const routes = [
  {
    path: '/',
    redirect: '/login',
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginPage,
    meta: { requiresAuth: false, guestOnly: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterPage,
    meta: { requiresAuth: false, guestOnly: true }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardPage,
    meta: { requiresAuth: true }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('user_token') !== null;

  if (to.meta.requiresAuth && !isAuthenticated) {
    console.log(`Redirecting from ${to.path} to /login: Requires auth and not authenticated.`);
    return next('/login');
  }
  else if (to.meta.guestOnly && isAuthenticated) {
    console.log(`Redirecting from ${to.path} to /dashboard: Already authenticated.`);
    return next('/dashboard');
  }
  else {
    console.log(`Allowing navigation to: ${to.path}. Auth status: ${isAuthenticated ? 'Authenticated' : 'Unauthenticated'}`);
    return next();
  }
});

export default router;