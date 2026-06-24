<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import EmptyState from '../components/EmptyState.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useToast } from '../composables/useToast'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const toast = useToast()
const cart = useCartStore()
const items = ref([])
const loading = ref(true)

const total = computed(() =>
  items.value.reduce((sum, item) => sum + (item.product?.price || 0) * item.quantity, 0)
)

onMounted(async () => {
  try {
    const res = await api.get('/carts')
    items.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

async function updateQty(item, qty) {
  if (qty < 1) return
  try {
    await api.put(`/carts/${item.id}`, { quantity: qty })
    item.quantity = qty
    cart.fetchCount()
  } catch (e) {
    toast.error('Error updating quantity')
  }
}

async function remove(itemId) {
  try {
    await api.delete(`/carts/${itemId}`)
    items.value = items.value.filter(i => i.id !== itemId)
    cart.fetchCount()
    toast.success('Item removed from cart')
  } catch (e) {
    toast.error('Error removing item')
  }
}

function checkout() {
  router.push('/checkout')
}
</script>

<template>
  <h1 class="mb-4">
    <i class="bi bi-cart me-2 text-primary"></i>Shopping Cart
  </h1>
  <LoadingSpinner v-if="loading" />
  <EmptyState
    v-else-if="items.length === 0"
    icon="bi-cart-x"
    title="Your cart is empty"
    message="Looks like you haven't added anything yet."
    linkTo="/products"
    linkText="Start Shopping"
  />
  <div v-else>
    <div v-for="item in items" :key="item.id" class="card mb-2 p-3 border-0 shadow-sm">
      <div class="row align-items-center">
        <div class="col-md-2">
          <img
            v-if="item.product?.image"
            :src="item.product?.image ? `/storage/${item.product.image}` : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 200%22%3E%3Crect width=%22200%22 height=%22200%22 fill=%22%23e2e8f0%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22sans-serif%22 font-size=%2224%22 fill=%22%2394a3b8%22%3ENo Image%3C/text%3E%3C/svg%3E'"
            class="img-fluid rounded"
            style="height: 80px; width: 100%; object-fit: cover;"
          />
          <div
            v-else
            class="bg-secondary bg-opacity-10 text-muted d-flex align-items-center justify-content-center rounded"
            style="height: 80px"
          >
            <i class="bi bi-image"></i>
          </div>
        </div>
        <div class="col-md-3">
          <h6 class="mb-1">{{ item.product?.name }}</h6>
          <p class="text-muted small mb-0">${{ item.product?.price }} each</p>
        </div>
        <div class="col-md-3">
          <div class="input-group input-group-sm" style="max-width: 130px">
            <button class="btn btn-outline-secondary" @click="updateQty(item, item.quantity - 1)">
              <i class="bi bi-dash"></i>
            </button>
            <span class="input-group-text bg-white fw-medium">{{ item.quantity }}</span>
            <button class="btn btn-outline-secondary" @click="updateQty(item, item.quantity + 1)">
              <i class="bi bi-plus"></i>
            </button>
          </div>
        </div>
        <div class="col-md-2">
          <strong class="text-primary">
            ${{ ((item.product?.price || 0) * item.quantity).toFixed(2) }}
          </strong>
        </div>
        <div class="col-md-2 text-end">
          <button class="btn btn-sm btn-outline-danger" @click="remove(item.id)">
            <i class="bi bi-trash me-1"></i>Remove
          </button>
        </div>
      </div>
    </div>
    <div class="card p-4 mt-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-muted">Total ({{ items.length }} item{{ items.length > 1 ? 's' : '' }})</span>
          <h3 class="mb-0 text-primary fw-bold">${{ total.toFixed(2) }}</h3>
        </div>
        <button class="btn btn-primary btn-lg" @click="checkout">
          <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
        </button>
      </div>
    </div>
  </div>
</template>
