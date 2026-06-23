<script setup>
import Navbar from './components/AppNavbar.vue'
import AppFooter from './components/AppFooter.vue'
import ToastNotification from './components/ToastNotification.vue'
</script>

<template>
  <div class="d-flex flex-column min-vh-100">
    <Navbar />
    <main class="container py-4 flex-grow-1">
      <router-view />
    </main>
    <AppFooter />
    <ToastNotification />
  </div>
</template>
