<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useToast } from '../composables/useToast'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const toast = useToast()
const cartStore = useCartStore()
const items = ref([])
const loading = ref(false)
const error = ref('')

const total = computed(() =>
  items.value.reduce((sum, item) => sum + ((item.product?.final_price ?? item.product?.price) || 0) * item.quantity, 0)
)

onMounted(async () => {
  try {
    const res = await api.get('/carts')
    items.value = res.data
    if (items.value.length === 0) router.push('/cart')
  } catch (e) { /* ignore */ }
})

async function placeOrder() {
  loading.value = true
  error.value = ''
  try {
    const res = await api.post('/orders')
    cartStore.reset()
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
  <div class="section-header">
    <span class="header-icon"><i class="bi bi-credit-card"></i></span>
    <h2>Checkout</h2>
    <span class="header-line"></span>
  </div>

  <div class="row g-4">
    <div class="col-md-8">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <h5 class="mb-3 fw-semibold">
            <i class="bi bi-receipt me-2"></i>Order Summary
          </h5>
          <div v-for="item in items" :key="item.id" class="d-flex justify-content-between py-2 border-bottom">
            <span>
              {{ item.product?.name }} <span class="text-muted">x{{ item.quantity }}</span>
              <span v-if="item.product?.has_discount" class="badge rounded-pill ms-1" style="background: #fee2e2; color: #dc2626; font-size: 10px;">
                -{{ item.product?.discount_percent }}%
              </span>
            </span>
            <span class="fw-semibold">${{ (((item.product?.final_price ?? item.product?.price) || 0) * item.quantity).toFixed(2) }}</span>
          </div>
          <p v-if="error" class="text-danger mt-2 small">
            <i class="bi bi-exclamation-circle me-1"></i>{{ error }}
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <h5 class="mb-3 fw-semibold">Order Total</h5>
          <h2 class="text-primary fw-bold mb-4" style="font-size: 32px;">${{ total.toFixed(2) }}</h2>
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
  </div>
</template>
