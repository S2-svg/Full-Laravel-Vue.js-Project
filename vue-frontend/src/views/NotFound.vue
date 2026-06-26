<script setup>
import { useRouter } from 'vue-router'
const router = useRouter()
</script>

<template>
  <div class="text-center py-5 mt-5 fade-in-up">
    <div class="display-1 fw-bold" style="font-size: 100px; line-height: 1; background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">404</div>
    <h4 class="text-muted mb-2 mt-2">Page Not Found</h4>
    <p class="text-muted mb-4" style="max-width: 420px; margin-left: auto; margin-right: auto;">The page you're looking for doesn't exist or has been moved.</p>
    <button class="btn btn-primary btn-lg px-4" @click="router.push('/')">
      <i class="bi bi-house me-2"></i>Back to Home
    </button>
  </div>
</template>
