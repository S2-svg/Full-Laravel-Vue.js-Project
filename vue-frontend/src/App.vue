<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from './components/AppNavbar.vue'
import AppFooter from './components/AppFooter.vue'
import ToastNotification from './components/ToastNotification.vue'

const route = useRoute()
const isAdminRoute = computed(() => route.path.startsWith('/admin'))
</script>

<template>
  <template v-if="isAdminRoute">
    <router-view />
    <ToastNotification />
  </template>
  <template v-else>
    <div class="d-flex flex-column min-vh-100">
      <Navbar />
      <main class="container py-4 flex-grow-1">
        <router-view />
      </main>
      <AppFooter />
      <ToastNotification />
    </div>
  </template>
</template>
