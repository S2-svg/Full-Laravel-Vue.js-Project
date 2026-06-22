<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useToast } from '../composables/useToast'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const toast = useToast()
const cart = useCartStore()
const items = ref([])
const loading = ref(false)
const error = ref('')

const total = computed(() =>
  items.value.reduce((sum, item) => sum + (item.product?.price || 0) * item.quantity, 0)
)

onMounted(async () => {
  try {
    const res = await api.get('/carts')
    items.value = res.data
    if (items.value.length === 0) router.push('/cart')
  } catch (e) { console.error(e) }
})

async function placeOrder() {
  loading.value = true
  error.value = ''
  try {
    const res = await api.post('/orders')
    cart.reset()
    toast.success('Order placed successfully!')
    router.push(`/orders/${res.data.id}`)
  } catch (e) {
    error.value = e.response?.data?.message || 'Error placing order'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <h1 class="mb-4">
    <i class="bi bi-credit-card me-2 text-primary"></i>Checkout
  </h1>
  <div class="row">
    <div class="col-md-8">
      <div class="card border-0 shadow-sm p-3">
        <h5 class="mb-3">
          <i class="bi bi-receipt me-2"></i>Order Summary
        </h5>
        <div v-for="item in items" :key="item.id" class="d-flex justify-content-between py-2 border-bottom">
          <span>{{ item.product?.name }} <span class="text-muted">x{{ item.quantity }}</span></span>
          <span class="fw-medium">${{ ((item.product?.price || 0) * item.quantity).toFixed(2) }}</span>
        </div>
        <p v-if="error" class="text-danger mt-2 small">
          <i class="bi bi-exclamation-circle me-1"></i>{{ error }}
        </p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm p-4">
        <h5 class="mb-3">Order Total</h5>
        <h2 class="text-primary fw-bold mb-3">${{ total.toFixed(2) }}</h2>
        <button
          class="btn btn-primary btn-lg w-100"
          @click="placeOrder"
          :disabled="loading"
        >
          <i v-if="loading" class="bi bi-hourglass-split me-2"></i>
          <i v-else class="bi bi-check-lg me-2"></i>
          {{ loading ? 'Processing...' : 'Place Order' }}
        </button>
      </div>
    </div>
  </div>
</template>
