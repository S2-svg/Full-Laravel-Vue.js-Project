<script setup>
defineProps({
  rating: { type: Number, required: true },
  max: { type: Number, default: 5 },
})
</script>

<template>
  <span class="star-rating">
    <i
      v-for="i in max"
      :key="i"
      :class="i <= rating ? 'bi bi-star-fill text-warning' : 'bi bi-star text-warning'"
    ></i>
  </span>
</template>
