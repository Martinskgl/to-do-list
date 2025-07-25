import { createRouter, createWebHistory } from 'vue-router';
import LoginView from './pages/LoginView.vue';
import TasksView from './pages/TasksView.vue';
import UserView from './pages/UserView.vue';
import AuthService from './Auth/AuthService';
import CreateTaskView from './pages/admin/CreateTaskView.vue';
import CreateUserView from './pages/admin/CreateUserView.vue';
import EditTaskView from './pages/admin/EditTaskView.vue';
import EditUserView from './pages/admin/EditUserView.vue';
const routes = [
  {
    path: '/',
    redirect: '/login',
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
    meta: { requiresGuest: true },
  },
  {
    path: '/tasks',
    name: 'Tasks',
    component: TasksView,
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/tasks/create',
    name: 'AdminCreateTask',
    component: CreateTaskView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    path: '/admin/tasks/edit/:id',
    name: 'AdminEditTask',
    component: EditTaskView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    path: '/admin/users/edit/:id',
    name: 'AdminEditUser',
    component: EditUserView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    path: '/admin/users/create',
    name: 'AdminCreateUser',
    component: CreateUserView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    path: '/admin/users',
    name: 'AdminUsersList',
    component: UserView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

function checkAuthRequired(to, isAuthenticated) {
  return to.meta.requiresAuth && !isAuthenticated;
}

function requiresAdmin(to) {
  return to.meta.requiresAdmin && !AuthService.isAdmin();
}

function requiresGuest(to, isAuthenticated) {
  return to.meta.requiresGuest && isAuthenticated;
}

router.beforeEach((to, from, next) => {
  const isAuthenticated = AuthService.isAuthenticated();

  if (checkAuthRequired(to, isAuthenticated)) {
    return next('/login');
  }

  if (requiresAdmin(to)) {
    return next('/tasks');
  }

  if (requiresGuest(to, isAuthenticated)) {
    return next('/tasks');
  }

  next();
});

export default router;
