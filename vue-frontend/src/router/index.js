import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', name: 'Home', component: () => import('../views/Home.vue') },
  { path: '/products', name: 'Products', component: () => import('../views/Products.vue') },
  { path: '/products/:id', name: 'ProductDetail', component: () => import('../views/ProductDetail.vue') },
  { path: '/login', name: 'Login', component: () => import('../views/Login.vue') },
  { path: '/register', name: 'Register', component: () => import('../views/Register.vue') },
  { path: '/wishlist', name: 'Wishlist', component: () => import('../views/Wishlist.vue'), meta: { requiresAuth: true } },
  { path: '/cart', name: 'Cart', component: () => import('../views/Cart.vue'), meta: { requiresAuth: true } },
  { path: '/checkout', name: 'Checkout', component: () => import('../views/Checkout.vue'), meta: { requiresAuth: true } },
  { path: '/orders', name: 'Orders', component: () => import('../views/Orders.vue'), meta: { requiresAuth: true } },
  { path: '/orders/:id', name: 'OrderDetail', component: () => import('../views/OrderDetail.vue'), meta: { requiresAuth: true } },
  { path: '/profile', name: 'Profile', component: () => import('../views/Profile.vue'), meta: { requiresAuth: true } },
  {
    path: '/admin',
    component: () => import('../views/AdminLayout.vue'),
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      { path: '', name: 'AdminDashboard', component: () => import('../views/AdminDashboard.vue') },
      { path: 'categories', name: 'AdminCategories', component: () => import('../views/AdminCategories.vue') },
      { path: 'products', name: 'AdminProducts', component: () => import('../views/AdminProducts.vue') },
      { path: 'products/create', name: 'AdminProductCreate', component: () => import('../views/AdminProductForm.vue') },
      { path: 'products/:id/edit', name: 'AdminProductEdit', component: () => import('../views/AdminProductForm.vue') },
      { path: 'orders', name: 'AdminOrders', component: () => import('../views/AdminOrders.vue') },
      { path: 'orders/:id', name: 'AdminOrderDetail', component: () => import('../views/AdminOrderDetail.vue') },
      { path: 'users', name: 'AdminUsers', component: () => import('../views/AdminUsers.vue') },
    ],
  },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: () => import('../views/NotFound.vue') },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || 'null')

  if (to.meta.requiresAuth && !token) {
    next({ name: 'Login' })
  } else if (to.meta.requiresAdmin && user?.role !== 'admin') {
    next({ name: 'Home' })
  } else {
    next()
  }
})

export default router
