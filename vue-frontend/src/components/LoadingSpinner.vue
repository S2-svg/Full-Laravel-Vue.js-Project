<script setup>
defineProps({
  text: { type: String, default: 'Loading...' },
})
</script>

<template>
  <div class="text-center py-5">
    <div class="spinner-border mb-2" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <p class="text-muted">{{ text }}</p>
  </div>
</template>
