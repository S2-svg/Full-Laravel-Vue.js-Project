<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()
</script>

<template>
  <div class="text-center py-5 mt-5">
    <h1 class="display-1 fw-bold text-primary">404</h1>
    <h4 class="text-muted mb-3">Page Not Found</h4>
    <p class="text-muted mb-4">The page you're looking for doesn't exist or has been moved.</p>
    <button class="btn btn-primary" @click="router.push('/')">
      <i class="bi bi-house me-2"></i>Back to Home
    </button>
  </div>
</template>
