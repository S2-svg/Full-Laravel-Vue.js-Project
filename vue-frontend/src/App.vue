<<<<<<< HEAD
=======
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
        <router-view v-slot="{ Component }">
          <transition name="page" mode="out-in">
            <component :is="Component" :key="route.path" />
          </transition>
        </router-view>
      </main>
      <AppFooter />
      <ToastNotification />
    </div>
  </template>
</template>

<style>
.page-enter-active,
.page-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}

.page-enter-from {
  opacity: 0;
  transform: translateY(6px);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>
>>>>>>> 15befe42a6d9c0eab14c68956c67a6b274a6d8cb
