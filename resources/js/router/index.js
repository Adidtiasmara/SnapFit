import { createRouter, createWebHistory } from 'vue-router';

/**
 * SnapFit Router
 * ─────────────────────────────────────────────────
 * Menggunakan lazy-loading (dynamic import) pada setiap route
 * agar initial bundle kecil dan performa optimal.
 */
const routes = [
  // ─── PUBLIC ─────────────────────────────────────
  {
    path: '/',
    name: 'landing',
    component: () => import('@/pages/landing/index.vue'),
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/pages/auth/login.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/pages/auth/register.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/password/reset/:token',
    name: 'password.reset',
    component: () => import('@/pages/auth/reset-password.vue'),
    meta: { guestOnly: true },
  },
  {
    path: '/marketplace',
    redirect: '/',
  },
  {
    path: '/product/:id',
    redirect: to => `/${to.params.id}`,
  },
  {
    path: '/cart',
    name: 'cart',
    component: () => import('@/pages/landing/marketplace/cart.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/checkout',
    name: 'checkout',
    component: () => import('@/pages/landing/marketplace/checkout.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/qris',
    redirect: '/profile?tab=orders',
  },
  {
    path: '/orders',
    redirect: '/profile?tab=orders',
  },
  {
    path: '/orders/:id',
    name: 'order.detail',
    component: () => import('@/pages/landing/marketplace/order-detail.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/profile',
    name: 'profile',
    component: () => import('@/pages/landing/profile/index.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/notifications',
    name: 'notifications',
    component: () => import('@/pages/landing/notifications.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/shop/:id',
    name: 'shop.detail',
    component: () => import('@/pages/landing/marketplace/shop.vue'),
  },
  {
    path: '/register/umkm',
    name: 'register.umkm',
    component: () => import('@/pages/landing/register/umkm.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/register/designer',
    name: 'register.designer',
    component: () => import('@/pages/landing/register/designer.vue'),
    meta: { requiresAuth: true },
  },

  // ─── UMKM ───────────────────────────────────────
  {
    path: '/umkm',
    component: () => import('@/layouts/umkm.vue'),
    meta: { requiresAuth: true, role: 'umkm' },
    children: [
      {
        path: 'dashboard',
        name: 'umkm.dashboard',
        component: () => import('@/pages/umkm/dashboard/index.vue'),
      },
      {
        path: 'studio',
        name: 'umkm.studio',
        component: () => import('@/pages/umkm/studio/index.vue'),
      },
      {
        path: 'products',
        name: 'umkm.products',
        component: () => import('@/pages/umkm/products/index.vue'),
      },
      {
        path: 'products/create',
        name: 'umkm.products.create',
        component: () => import('@/pages/umkm/products/create.vue'),
      },
      {
        path: 'products/edit/:id',
        name: 'umkm.products.edit',
        component: () => import('@/pages/umkm/products/edit/[id].vue'),
      },
      {
        path: 'cocreate',
        name: 'umkm.cocreate.index',
        component: () => import('@/pages/umkm/cocreate/index.vue'),
      },
      {
        path: 'cocreate/:id',
        name: 'umkm.cocreate',
        component: () => import('@/pages/umkm/cocreate/[id].vue'),
      },
      {
        path: 'orders',
        name: 'umkm.orders',
        component: () => import('@/pages/umkm/orders/index.vue'),
      },
      {
        path: 'analytics',
        name: 'umkm.analytics',
        component: () => import('@/pages/umkm/analytics/index.vue'),
      },
      {
        path: 'settings',
        name: 'umkm.settings',
        component: () => import('@/pages/umkm/settings/index.vue'),
      },
    ],
  },

  // ─── DESIGNER ───────────────────────────────────
  {
    path: '/designer',
    component: () => import('@/layouts/designer.vue'),
    meta: { requiresAuth: true, role: 'designer' },
    children: [
      {
        path: 'dashboard',
        name: 'designer.dashboard',
        component: () => import('@/pages/designer/dashboard/index.vue'),
      },
      {
        path: 'cocreate',
        name: 'designer.cocreate.index',
        component: () => import('@/pages/designer/cocreate/index.vue'),
      },
      {
        path: 'cocreate/:id',
        name: 'designer.cocreate.detail',
        component: () => import('@/pages/designer/cocreate/[id].vue'),
      },
      {
        path: 'portfolio',
        name: 'designer.portfolio',
        component: () => import('@/pages/designer/portfolio/index.vue'),
      },
      {
        path: 'history',
        name: 'designer.history',
        component: () => import('@/pages/designer/history/index.vue'),
      },
      {
        path: 'settings',
        name: 'designer.settings',
        component: () => import('@/pages/designer/settings/index.vue'),
      },
    ],
  },

  // ─── ADMIN ──────────────────────────────────────
  {
    path: '/admin',
    component: () => import('@/layouts/admin.vue'),
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      {
        path: 'dashboard',
        name: 'admin.dashboard',
        component: () => import('@/pages/admin/dashboard/index.vue'),
      },
      {
        path: 'users',
        name: 'admin.users',
        component: () => import('@/pages/admin/users/index.vue'),
      },
      {
        path: 'users/create',
        name: 'admin.users.create',
        component: () => import('@/pages/admin/users/create.vue'),
      },
      {
        path: 'users/edit/:id',
        name: 'admin.users.edit',
        component: () => import('@/pages/admin/users/edit/[id].vue'),
      },
      {
        path: 'products',
        name: 'admin.products',
        component: () => import('@/pages/admin/products/index.vue'),
      },
      {
        path: 'orders',
        name: 'admin.orders',
        component: () => import('@/pages/admin/orders/index.vue'),
      },
      {
        path: 'reviews',
        name: 'admin.reviews',
        component: () => import('@/pages/admin/reviews/index.vue'),
      },
      {
        path: 'analytics',
        name: 'admin.analytics',
        component: () => import('@/pages/admin/analytics/index.vue'),
      },
      {
        path: 'roles',
        name: 'admin.roles',
        component: () => import('@/pages/admin/roles/index.vue'),
      },
    ],
  },

  // ─── PRODUCT DETAIL (ROOT LEVEL SEO) ────────────
  {
    path: '/:id(\\d+)',
    name: 'product.detail',
    component: () => import('@/pages/landing/marketplace/product-detail.vue'),
  },
  {
    path: '/:slug-i.:id(\\d+)',
    name: 'product.detail.seo',
    component: () => import('@/pages/landing/marketplace/product-detail.vue'),
  },

  // ─── FALLBACK ───────────────────────────────────
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition;
    if (to.hash) return { el: to.hash, behavior: 'smooth' };
    return { top: 0, behavior: 'smooth' };
  },
});

const getRoleHome = (user) => {
  const role = user?.active_role;
  if (role === 'admin') return '/admin/dashboard';
  if (role === 'umkm') return '/umkm/dashboard';
  if (role === 'designer' || role === 'desainer') return '/designer/dashboard';
  return '/';
};

/* ── Navigation Guard ─────────────────────────── */
router.beforeEach((to, from) => {
  const user  = JSON.parse(localStorage.getItem('user') ?? 'null');
  const token = localStorage.getItem('token');
  const isAuthenticated = !!(token && user);

  // Redirect logged-in user away from guest-only pages
  if (to.meta.guestOnly && isAuthenticated) {
    return getRoleHome(user);
  }

  // Require authentication
  if (to.meta.requiresAuth && !isAuthenticated) {
    return '/login';
  }

  // Require specific role
  if (to.meta.role && user?.active_role !== to.meta.role) {
    // Also check if user has the role in their roles array
    const userRoles = user?.owned_roles || user?.roles?.map(r => r.name || r) || [];
    if (!userRoles.includes(to.meta.role) && user?.active_role !== to.meta.role) {
      return '/profile';
    }
  }

  return true;
});

export default router;
