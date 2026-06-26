<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useToast } from '../composables/useToast'
import { useCartStore } from '../stores/cart'

const route = useRoute()
const router = useRouter()
const order = ref(null)
const loading = ref(true)
const reordering = ref(false)

const toast = useToast()
const cartStore = useCartStore()

onMounted(async () => {
  try {
    const res = await api.get(`/orders/${route.params.id}`)
    order.value = res.data
  } catch {
    router.push('/orders')
  } finally {
    loading.value = false
  }
})

const statusBadge = (status) => ({
  'bg-success': status === 'completed',
  'bg-danger': status === 'cancelled',
  'bg-warning text-dark': status === 'pending' || status === 'processing',
})

async function reorder() {
  reordering.value = true
  try {
    const res = await api.post(`/orders/${route.params.id}/reorder`)
    toast.success(res.data.message || 'Items added to cart')
    await cartStore.fetchCount()
  } catch (e) {
    toast.error(e.response?.data?.message || 'Failed to reorder')
  } finally {
    reordering.value = false
  }
}
</script>

<template>
  <div v-if="order" class="fade-in-up">
    <router-link to="/orders" class="btn btn-outline-secondary mb-3">
      <i class="bi bi-arrow-left me-1"></i>Back to Orders
    </router-link>

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold">
          <i class="bi bi-receipt me-2 text-primary"></i>Order #{{ order.order_number }}
        </h5>
        <span class="badge rounded-pill" :class="statusBadge(order.status)">{{ order.status }}</span>
      </div>
      <div class="card-body">
        <p class="text-muted small mb-3">
          <i class="bi bi-calendar me-1"></i>Placed: {{ new Date(order.created_at).toLocaleDateString() }}
        </p>
        <div class="table-responsive">
          <table class="table">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th class="text-end">Price</th>
                <th class="text-center">Quantity</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in order.items" :key="item.id">
                <td class="fw-medium">{{ item.product?.name }}</td>
                <td class="text-end">${{ item.price }}</td>
                <td class="text-center">{{ item.quantity }}</td>
                <td class="text-end fw-semibold">${{ (item.price * item.quantity).toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-2">
          <button
            class="btn btn-outline-success"
            :disabled="reordering"
            @click="reorder()"
          >
            <i
              :class="reordering ? 'bi-arrow-repeat spinner' : 'bi-arrow-counterclockwise'"
              class="me-1"
            ></i>
            {{ reordering ? 'Adding to Cart...' : 'Reorder All' }}
          </button>
          <h4 class="mb-0 text-primary fw-bold">Total: ${{ order.total }}</h4>
        </div>
      </div>
    </div>
  </div>
  <LoadingSpinner v-else />
</template>

<style scoped>
.spinner {
  animation: spin 1s linear infinite;
  display: inline-block;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.btn-outline-success:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
</style>
