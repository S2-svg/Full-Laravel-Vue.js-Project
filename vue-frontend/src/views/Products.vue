<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import ProductCard from '../components/ProductCard.vue'
import { useCategoriesStore } from '../stores/categories'

const route = useRoute()
const router = useRouter()
const products = ref([])
const meta = ref({})
const searchQuery = ref(route.query.search || '')
const selectedCategory = ref(route.query.category_id || '')
const categoriesStore = useCategoriesStore()

async function fetchProducts() {
  const params = { page: route.query.page || 1 }
  if (route.query.search) params.search = route.query.search
  if (route.query.category_id) params.category_id = route.query.category_id
  const res = await api.get('/products', { params })
  products.value = res.data.data
  meta.value = { ...res.data }
  delete meta.value.data
}

onMounted(() => {
  categoriesStore.fetch()
  fetchProducts()
})

watch(() => route.query, (q) => {
  searchQuery.value = q.search || ''
  selectedCategory.value = q.category_id || ''
  fetchProducts()
})

function search() {
  router.push({ query: { search: searchQuery.value || undefined, category_id: selectedCategory.value || undefined } })
}

function goToPage(page) {
  router.push({ query: { ...route.query, page } })
}
</script>

<template>
  <div class="products-page">
    <div class="section-header">
      <span class="header-icon"><i class="bi bi-grid"></i></span>
      <h2>Products</h2>
      <span class="header-line"></span>
    </div>

    <div class="card border-0 shadow-sm mb-4 p-3">
      <div class="row g-2 align-items-center">
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
            <option v-for="cat in categoriesStore.items" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
        </div>
      </div>
    </div>

    <div v-if="products.length === 0" class="text-center py-5">
      <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
      <h5 class="text-muted">No products found</h5>
      <p class="text-muted">Try adjusting your search or filter criteria.</p>
    </div>
    <div v-else class="row">
      <div v-for="product in products" :key="product.id" class="col-6 col-md-3 mb-4">
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
        <li v-for="p in meta.last_page" :key="p" class="page-item" :class="{ active: p === meta.current_page }">
          <a class="page-link" href="#" @click.prevent="goToPage(p)">{{ p }}</a>
        </li>
        <li class="page-item" :class="{ disabled: meta.current_page >= meta.last_page }">
          <a class="page-link" href="#" @click.prevent="goToPage(meta.current_page + 1)">
            <i class="bi bi-chevron-right"></i>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>
