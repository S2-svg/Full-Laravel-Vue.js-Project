<script setup>
defineProps({
  product: { type: Object, required: true },
})
</script>

<template>
  <div class="card h-100 product-card border-0 shadow-sm">
    <router-link :to="`/products/${product.id}`">
      <img
        v-if="product.image"
          :src="product.image ? `/storage/${product.image}` : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 200%22%3E%3Crect width=%22200%22 height=%22200%22 fill=%22%23e2e8f0%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22sans-serif%22 font-size=%2224%22 fill=%22%2394a3b8%22%3ENo Image%3C/text%3E%3C/svg%3E'"
        class="card-img-top"
        alt=""
      />
      <div
        v-else
        class="card-img-top bg-light text-muted d-flex align-items-center justify-content-center"
        style="height: 200px"
      >
        <i class="bi bi-image fs-1"></i>
      </div>
    </router-link>
    <div class="card-body d-flex flex-column">
      <span class="category-pill mb-1">{{ product.category?.name }}</span>
      <h6 class="product-title">{{ product.name }}</h6>
      <div class="mt-auto">
        <span class="price">${{ product.price }}</span>
        <router-link :to="`/products/${product.id}`" class="btn btn-primary btn-sm w-100 mt-2">
          View Details
        </router-link>
      </div>
    </div>
  </div>
</template>
