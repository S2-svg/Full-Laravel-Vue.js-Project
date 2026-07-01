<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import ProductCard from '../components/ProductCard.vue'
import EmptyState from '../components/EmptyState.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useToast } from '../composables/useToast'
import { useWishlistStore } from '../stores/wishlist'

const toast = useToast()
const wishlist = useWishlistStore()
const items = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await api.get('/wishlists')
    items.value = res.data
  } finally {
    loading.value = false
  }
})

async function remove(id) {
  try {
    await api.delete(`/wishlists/${id}`)
    items.value = items.value.filter(i => i.id !== id)
    wishlist.decrement()
    toast.success('Removed from wishlist')
  } catch (e) {
    toast.error('Error removing item')
  }
}
</script>

<template>
  <div class="wishlist-page">
    <div class="section-header">
      <span class="header-icon"><i class="bi bi-heart"></i></span>
      <h2>My Wishlist</h2>
      <span class="header-line"></span>
    </div>

    <LoadingSpinner v-if="loading" />
    <EmptyState
      v-else-if="items.length === 0"
      icon="bi-heartbreak"
      title="Your wishlist is empty"
      message="Save items you love to your wishlist."
      linkTo="/products"
      linkText="Browse Products"
    />
    <div v-else class="row">
      <div v-for="item in items" :key="item.id" class="col-6 col-md-3 mb-4">
        <div class="card h-100 product-card border-0 shadow-sm">
          <div class="position-relative">
            <ProductCard :product="item.product" />
            <button
              class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle"
              style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;"
              @click="remove(item.id)"
              title="Remove"
            >
              <i class="bi bi-x"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
