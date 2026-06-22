<script setup>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import { computed, onMounted } from 'vue'

const auth = useAuthStore()
const router = useRouter()

onMounted(() => {
  if (!auth.isAdmin) {
    router.push('/')
  }
})

const navItems = [
  { to: '/admin', icon: 'bi-speedometer2', label: 'Dashboard' },
  { to: '/admin/categories', icon: 'bi-tags', label: 'Categories' },
  { to: '/admin/products', icon: 'bi-box', label: 'Products' },
  { to: '/admin/orders', icon: 'bi-receipt', label: 'Orders' },
  { to: '/admin/users', icon: 'bi-people', label: 'Users' },
]

const currentRoute = computed(() => router.currentRoute.value.path)
</script>

<template>
  <div class="d-flex">
    <div class="admin-sidebar bg-dark text-white p-3" style="width: 240px; min-height: calc(100vh - 56px); flex-shrink: 0;">
      <h6 class="text-white-50 small text-uppercase fw-bold px-3 mb-3">Admin Panel</h6>
      <nav class="nav flex-column">
        <router-link
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="nav-link text-white-50 rounded px-3 py-2 mb-1"
          :class="{ 'bg-primary text-white': currentRoute === item.to }"
        >
          <i :class="item.icon" class="me-2"></i>{{ item.label }}
        </router-link>
        <hr class="text-white-50 my-2" />
        <router-link to="/" class="nav-link text-white-50 rounded px-3 py-2">
          <i class="bi bi-arrow-left me-2"></i>Back to Store
        </router-link>
      </nav>
    </div>
    <div class="flex-grow-1 p-4" style="background: #f0f2f5; min-height: calc(100vh - 56px);">
      <router-view />
    </div>
  </div>
</template>
