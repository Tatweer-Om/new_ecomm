import { createRouter, createWebHistory } from 'vue-router';
import Users from './components/Users.vue';
import Login from './components/Login.vue';
import axios from 'axios'; 

const routes = [
  { path: '/users', name: 'users', component: Users, meta: { requiresAuth: true } },
  { path: '/tshowLogin', name: 'tshowLogin', component: Login },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// 👇 Navigation guard
router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    try {
      const res = await axios.get('/api/check-auth');
      if (res.data.authenticated) next();
      else next('/tshowLogin');
    } catch (err) {
      next('/tshowLogin');
    }
  } else {
    next();
  }
});

export default router;
