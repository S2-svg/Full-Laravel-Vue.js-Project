<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import ProductCard from '../components/ProductCard.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'

const featuredProducts = ref([])
const categories = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const [prodRes, catRes] = await Promise.all([
      api.get('/products?per_page=8'),
      api.get('/categories'),
    ])
    featuredProducts.value = prodRes.data.data || []
    categories.value = catRes.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="hero-section text-center mb-5">
    <h1 class="display-4 fw-bold">Welcome to Our Store</h1>
    <p class="lead mb-4 opacity-90">Discover amazing products at unbeatable prices</p>
    <router-link to="/products" class="btn btn-light btn-lg px-5 fw-bold">
      <i class="bi bi-bag me-2"></i>Shop Now
    </router-link>
  </div>

  <h2 class="mb-4 d-flex align-items-center gap-2">
    <span class="section-icon"><i class="bi bi-collection"></i></span>
    Categories
  </h2>
  <div class="row mb-5">
    <div v-for="cat in categories" :key="cat.id" class="col-md-3 mb-3">
      <router-link :to="`/products?category_id=${cat.id}`" class="text-decoration-none">
        <div class="card category-card h-100 border-0 shadow-sm">
          <div class="card-body d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-tag text-primary"></i>
            <span class="fw-semibold">{{ cat.name }}</span>
          </div>
        </div>
      </router-link>
    </div>
  </div>

  <h2 class="mb-4 d-flex align-items-center gap-2">
    <span class="section-icon"><i class="bi bi-star"></i></span>
    Featured Products
  </h2>
  <LoadingSpinner v-if="loading" />
  <div v-else class="row">
    <div v-for="product in featuredProducts" :key="product.id" class="col-md-3 mb-4">
      <ProductCard :product="product" />
    </div>
  </div>
</template>
