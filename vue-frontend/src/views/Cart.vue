<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import EmptyState from '../components/EmptyState.vue'
import { useToast } from '../composables/useToast'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const toast = useToast()
const cartStore = useCartStore()
const items = ref([])
const ready = ref(false)

const total = computed(() =>
  items.value.reduce((sum, item) => sum + ((item.product?.final_price ?? item.product?.price) || 0) * item.quantity, 0)
)

const hasStockIssues = computed(() => items.value.some(item => item.product && item.product.stock <= 0))
const stockIssueItems = computed(() => items.value.filter(item => item.product && item.product.stock <= 0))
const lowStockItems = computed(() => items.value.filter(item => item.product && item.product.stock > 0 && item.product.stock <= 5))

onMounted(async () => {
  try {
    const res = await api.get('/carts')
    items.value = res.data
  } finally { ready.value = true }
})

async function updateQty(item, qty) {
  if (qty < 1) return
  try {
    await api.put(`/carts/${item.id}`, { quantity: qty })
    item.quantity = qty
    cartStore.fetchCount()
  } catch (e) { toast.error(e.response?.data?.message || 'Error updating quantity') }
}

async function remove(itemId) {
  try {
    await api.delete(`/carts/${itemId}`)
    items.value = items.value.filter(i => i.id !== itemId)
    cartStore.fetchCount()
    toast.success('Item removed from cart')
  } catch (e) { toast.error('Error removing item') }
}

function checkout() {
  if (hasStockIssues.value) return
  router.push('/checkout')
}
</script>

<template>
  <div class="section-header">
    <span class="header-icon"><i class="bi bi-cart3"></i></span>
    <h2>Shopping Cart</h2>
    <span class="header-line"></span>
  </div>

  <EmptyState v-if="ready && items.length === 0" icon="bi-cart-x" title="Your cart is empty" message="Looks like you haven't added anything yet." linkTo="/products" linkText="Start Shopping" />
  <div v-else-if="items.length === 0" class="text-center py-4 text-muted">
    <i class="bi bi-cart3 fs-1 d-block mb-2"></i>
    <p>Loading your cart...</p>
  </div>
  <div v-else>
    <div v-if="stockIssueItems.length > 0" class="alert alert-danger d-flex align-items-center gap-2 mb-3 stock-alert">
      <i class="bi bi-exclamation-triangle-fill fs-5"></i>
      <span><strong>Out of stock:</strong> <span v-for="(item, i) in stockIssueItems" :key="item.id">{{ item.product?.name }}<span v-if="i < stockIssueItems.length - 1">, </span></span> — please remove these items to proceed.</span>
    </div>
    <div v-if="lowStockItems.length > 0" class="alert alert-warning d-flex align-items-center gap-2 mb-3 stock-alert">
      <i class="bi bi-exclamation-circle-fill fs-5"></i>
      <span><strong>Low stock:</strong> <span v-for="(item, i) in lowStockItems" :key="item.id">{{ item.product?.name }} ({{ item.product?.stock }} left)<span v-if="i < lowStockItems.length - 1">, </span></span> — quantities may be limited at checkout.</span>
    </div>
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body p-0">
        <div v-for="(item, idx) in items" :key="item.id" class="p-4 border-bottom" :class="{ 'border-0': idx === items.length - 1 }">
          <div class="row align-items-center g-3">
            <div class="col-md-2">
<<<<<<< HEAD
              <img v-if="item.product?.image" :src="`/storage/${item.product.image}`" class="img-fluid rounded cart-item-img" loading="lazy" />
              <div v-else class="bg-light text-muted d-flex align-items-center justify-content-center rounded cart-item-img-placeholder"><i class="bi bi-image"></i></div>
=======
              <img
                v-if="item.product?.image"
                :src="`/storage/${item.product.image}`"
                class="img-fluid rounded"
                style="height: 80px; width: 100%; object-fit: cover;"
                loading="lazy"
              />
              <div
                v-else
                class="bg-light text-muted d-flex align-items-center justify-content-center rounded"
                style="height: 80px"
              >
                <i class="bi bi-image"></i>
              </div>
>>>>>>> 31c469c6f899e95dac5485e91348bfb77f86852c
            </div>
            <div class="col-md-3">
              <h6 class="mb-1 fw-semibold">{{ item.product?.name }}</h6>
              <div class="d-flex align-items-center gap-2">
                <p class="text-muted small mb-0">${{ item.product?.final_price ?? item.product?.price }} each</p>
                <span v-if="item.product?.has_discount" class="small text-muted text-decoration-line-through">${{ item.product?.price }}</span>
                <span v-if="item.product?.has_discount" class="badge rounded-pill cart-discount-badge">-{{ item.product?.discount_percent }}%</span>
              </div>
              <span v-if="item.product && item.product.stock <= 0" class="badge rounded-pill mt-1 stock-badge-out"><i class="bi bi-x-circle me-1"></i>Out of Stock</span>
              <span v-else-if="item.product && item.product.stock <= 5" class="badge rounded-pill mt-1 stock-badge-low"><i class="bi bi-exclamation-circle me-1"></i>Only {{ item.product.stock }} left</span>
            </div>
            <div class="col-md-3">
              <div class="d-flex align-items-center gap-2">
                <button class="qty-btn" :disabled="item.product?.stock <= 0" @click="updateQty(item, item.quantity - 1)"><i class="bi bi-dash"></i></button>
                <span class="qty-display" :class="{ 'text-danger': item.product?.stock <= 0 }">{{ item.quantity }}</span>
                <button class="qty-btn" :disabled="item.product?.stock <= 0" @click="updateQty(item, item.quantity + 1)"><i class="bi bi-plus"></i></button>
              </div>
            </div>
            <div class="col-md-2">
              <strong class="fw-bold" :class="item.product?.stock <= 0 ? 'text-danger' : 'text-primary'">${{ (((item.product?.final_price ?? item.product?.price) || 0) * item.quantity).toFixed(2) }}</strong>
            </div>
            <div class="col-md-2 text-end">
              <button class="btn btn-sm btn-outline-danger" @click="remove(item.id)"><i class="bi bi-trash me-1"></i>Remove</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card border-0 shadow-sm p-4">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-muted small">Total ({{ items.length }} item{{ items.length > 1 ? 's' : '' }})</span>
          <h3 class="mb-0 text-primary fw-bold">${{ total.toFixed(2) }}</h3>
        </div>
        <button class="btn btn-lg px-5" :class="hasStockIssues ? 'btn-secondary' : 'btn-primary'" :disabled="hasStockIssues" @click="checkout">
          <i class="bi bi-credit-card me-2"></i>
          {{ hasStockIssues ? 'Remove out of stock items' : 'Proceed to Checkout' }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.stock-alert { border: none; border-radius: 12px; font-size: 14px; }
.cart-item-img { height: 80px; width: 100%; object-fit: cover; }
.cart-item-img-placeholder { height: 80px; }
.cart-discount-badge { background: #fee2e2; color: #dc2626; font-size: 10px; }
.stock-badge-out { background: #fee2e2; color: #991b1b; font-size: 11px; }
.stock-badge-low { background: #fef3c7; color: #92400e; font-size: 11px; }
</style>
