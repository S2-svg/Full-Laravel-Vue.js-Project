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
        :src="`/storage/${product.image}`"
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
