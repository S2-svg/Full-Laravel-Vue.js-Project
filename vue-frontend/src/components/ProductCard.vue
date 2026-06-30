<script setup>
import { computed } from 'vue'

const props = defineProps({
  product: { type: Object, required: true },
})

const isOutOfStock = computed(() => props.product.stock <= 0)
const showPrice = computed(() => props.product.final_price ?? props.product.price)
</script>

<template>
  <div class="card h-100 product-card border-0 shadow-sm" :class="{ 'product-out-of-stock': isOutOfStock }">
    <router-link :to="`/products/${product.id}`" class="position-relative">
      <img
        v-if="product.image"
        :src="`/storage/${product.image}`"
        class="card-img-top"
        alt=""
        loading="lazy"
      />
      <div
        v-else
        class="card-img-top bg-light text-muted d-flex align-items-center justify-content-center card-img-placeholder"
      >
        <i class="bi bi-image fs-1"></i>
      </div>

      <div
        v-if="product.has_discount && !isOutOfStock"
        class="position-absolute top-0 start-0 m-2"
      >
        <span class="badge rounded-pill px-2 py-1 card-discount-badge">
          -{{ product.discount_percent }}%
        </span>
      </div>

      <div
        v-if="isOutOfStock"
        class="position-absolute top-0 start-0 end-0 bottom-0 d-flex align-items-center justify-content-center stock-overlay"
      >
        <span class="badge rounded-pill px-3 py-2 stock-badge">
          <i class="bi bi-x-circle me-1"></i>Out of Stock
        </span>
      </div>
    </router-link>
    <div class="card-body d-flex flex-column">
      <span class="category-pill mb-1">{{ product.category?.name }}</span>
      <h6 class="product-title">{{ product.name }}</h6>
      <div class="mt-auto">
        <div class="d-flex align-items-center gap-2">
          <span v-if="product.has_discount" class="price text-danger fw-bold">${{ showPrice }}</span>
          <span v-else class="price" :class="{ 'text-muted': isOutOfStock }">${{ showPrice }}</span>
          <span v-if="product.has_discount" class="small text-muted text-decoration-line-through">${{ product.price }}</span>
        </div>
        <router-link
          :to="`/products/${product.id}`"
          class="btn btn-sm w-100 mt-2"
          :class="isOutOfStock ? 'btn-outline-secondary' : 'btn-primary'"
        >
          {{ isOutOfStock ? 'View Details' : 'View Details' }}
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card-img-placeholder {
  height: 200px;
}

.card-discount-badge {
  background: #ef4444;
  font-size: 12px;
  font-weight: 700;
}

.stock-overlay {
  background: rgba(255,255,255,0.7);
  backdrop-filter: blur(2px);
}

.stock-badge {
  background: #ef4444;
  font-size: 13px;
  font-weight: 600;
}
</style>
