<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import ProductCard from '../components/ProductCard.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'

const route = useRoute()
const router = useRouter()
const products = ref([])
const categories = ref([])
const loading = ref(false)
const searchQuery = ref(route.query.search || '')
const selectedCategory = ref(route.query.category_id || '')
const meta = ref({})

async function fetchProducts() {
  loading.value = true
  try {
    const params = { page: route.query.page || 1 }
    if (searchQuery.value) params.search = searchQuery.value
    if (selectedCategory.value) params.category_id = selectedCategory.value
    const res = await api.get('/products', { params })
    products.value = res.data.data
    meta.value = { ...res.data }
    delete meta.value.data
  } catch (e) { console.error(e) }
  finally { loading.value = false }
}

async function fetchCategories() {
  try {
    const res = await api.get('/categories')
    categories.value = res.data
  } catch (e) { console.error(e) }
}

onMounted(() => {
  fetchCategories()
  fetchProducts()
})

watch(() => route.query, () => fetchProducts())

function search() {
  router.push({ query: { search: searchQuery.value, category_id: selectedCategory.value || undefined } })
}

function goToPage(page) {
  router.push({ query: { ...route.query, page } })
}
</script>

<template>
  <h1 class="mb-4">
    <i class="bi bi-grid me-2 text-primary"></i>Products
  </h1>

  <div class="row mb-4 g-2">
    <div class="col-md-5">
      <div class="input-group">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
        <input v-model="searchQuery" class="form-control" placeholder="Search products..." @keyup.enter="search">
        <button class="btn btn-primary" @click="search">Search</button>
      </div>
    </div>
    <div class="col-md-3">
      <select v-model="selectedCategory" class="form-select" @change="search">
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
      </select>
    </div>
  </div>

  <LoadingSpinner v-if="loading" />
  <div v-else-if="products.length === 0" class="text-center py-5">
    <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
    <h5 class="text-muted">No products found</h5>
    <p class="text-muted">Try adjusting your search or filter criteria.</p>
  </div>
  <div v-else class="row">
    <div v-for="product in products" :key="product.id" class="col-md-3 mb-4">
      <ProductCard :product="product" />
    </div>
  </div>

  <nav v-if="meta.last_page > 1" class="mt-4">
    <ul class="pagination justify-content-center">
      <li class="page-item" :class="{ disabled: meta.current_page <= 1 }">
        <a class="page-link" href="#" @click.prevent="goToPage(meta.current_page - 1)">
          <i class="bi bi-chevron-left"></i>
        </a>
      </li>
      <li
        v-for="p in meta.last_page"
        :key="p"
        class="page-item"
        :class="{ active: p === meta.current_page }"
      >
        <a class="page-link" href="#" @click.prevent="goToPage(p)">{{ p }}</a>
      </li>
      <li class="page-item" :class="{ disabled: meta.current_page >= meta.last_page }">
        <a class="page-link" href="#" @click.prevent="goToPage(meta.current_page + 1)">
          <i class="bi bi-chevron-right"></i>
        </a>
      </li>
    </ul>
  </nav>
</template>
