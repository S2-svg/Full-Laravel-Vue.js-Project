<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import { useToast } from '../composables/useToast'

const toast = useToast()
const router = useRouter()
const products = ref([])
const loading = ref(true)

onMounted(fetchProducts)

async function fetchProducts() {
  loading.value = true
  try {
    const res = await api.get('/admin/products')
    products.value = res.data
  } catch {
    toast.error('Failed to load products')
  } finally {
    loading.value = false
  }
}

async function deleteProduct(id) {
  if (!confirm('Delete this product?')) return
  try {
    await api.delete(`/admin/products/${id}`)
    toast.success('Product deleted')
    await fetchProducts()
  } catch {
    toast.error('Error deleting product')
  }
}
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0"><i class="bi bi-box me-2 text-primary"></i>Products</h4>
      <router-link to="/admin/products/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus me-1"></i>Add Product
      </router-link>
    </div>

    <div v-if="loading" class="text-center py-4">
      <div class="spinner-border"></div>
    </div>
    <div v-else-if="products.length === 0" class="text-center py-4 text-muted">
      <i class="bi bi-box fs-1 d-block mb-2"></i>
      <p>No products yet.</p>
    </div>
    <div v-else class="table-responsive">
      <table class="table table-hover bg-white rounded shadow-sm">
        <thead class="table-light">
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in products" :key="p.id">
            <td>
              <img
                v-if="p.image"
                :src="`/storage/${p.image}`"
                style="width: 48px; height: 48px; object-fit: cover;"
                class="rounded"
              />
              <div v-else class="bg-secondary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-image text-muted"></i>
              </div>
            </td>
            <td class="fw-medium">{{ p.name }}</td>
            <td><span class="badge bg-secondary">{{ p.category?.name }}</span></td>
            <td>${{ p.price }}</td>
            <td>
              <span :class="p.stock > 0 ? 'text-success' : 'text-danger'">{{ p.stock }}</span>
            </td>
            <td class="text-end">
              <router-link :to="`/admin/products/${p.id}/edit`" class="btn btn-sm btn-outline-primary me-1">
                <i class="bi bi-pencil"></i>
              </router-link>
              <button class="btn btn-sm btn-outline-danger" @click="deleteProduct(p.id)">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
