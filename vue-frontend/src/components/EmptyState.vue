<script setup>
defineProps({
  icon: { type: String, default: 'bi-inbox' },
  title: { type: String, default: 'Nothing here' },
  message: { type: String, default: '' },
  linkTo: { type: String, default: '' },
  linkText: { type: String, default: '' },
})
</script>

<template>
  <div class="text-center py-5">
    <i :class="'bi ' + icon + ' fs-1 text-muted mb-3 d-block'"></i>
    <h5 class="text-muted">{{ title }}</h5>
    <p v-if="message" class="text-muted">{{ message }}</p>
    <router-link v-if="linkTo" :to="linkTo" class="btn btn-primary mt-2">
      {{ linkText }}
    </router-link>
  </div>
</template>
