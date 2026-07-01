<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import EmptyState from '../components/EmptyState.vue'
import { useToast } from '../composables/useToast'
import { useCartStore } from '../stores/cart'

const orders = ref([])
const ready = ref(false)
const reordering = ref(null)

const router = useRouter()
const toast = useToast()
const cartStore = useCartStore()

onMounted(async () => {
  try {
    const res = await api.get('/orders')
    orders.value = res.data
  } finally { ready.value = true }
})

const statusBadge = (status) => ({
  'bg-success': status === 'completed',
  'bg-danger': status === 'cancelled',
  'bg-warning text-dark': status === 'pending' || status === 'processing',
})

async function reorder(orderId) {
  reordering.value = orderId
  try {
    const res = await api.post(`/orders/${orderId}/reorder`)
    toast.success(res.data.message || 'Items added to cart')
    await cartStore.fetchCount()
    router.push('/cart')
  } catch (e) {
    toast.error(e.response?.data?.message || 'Failed to reorder')
  } finally { reordering.value = null }
}
</script>

<template>
  <div class="orders-page">
    <div class="section-header">
      <span class="header-icon"><i class="bi bi-box"></i></span>
      <h2>My Orders</h2>
      <span class="header-line"></span>
    </div>

    <EmptyState v-if="ready && orders.length === 0" icon="bi-box" title="No orders yet" message="You haven't placed any orders yet." linkTo="/products" linkText="Start Shopping" />
    <div v-else-if="!ready" class="text-center py-4 text-muted">
      <i class="bi bi-box fs-1 d-block mb-2"></i>
      <p>Loading orders...</p>
    </div>
    <div v-else>
      <div v-for="order in orders" :key="order.id" class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
          <span class="fw-semibold"><i class="bi bi-receipt me-1"></i>Order #{{ order.order_number }}</span>
          <span class="badge rounded-pill" :class="statusBadge(order.status)">{{ order.status }}</span>
        </div>
        <div class="card-body">
          <p class="text-muted small mb-3"><i class="bi bi-calendar me-1"></i>Placed: {{ new Date(order.created_at).toLocaleDateString() }}</p>
          <div class="table-responsive">
            <table class="table table-sm mb-3">
              <thead class="table-light">
                <tr><th>Product</th><th>Qty</th><th class="text-end">Price</th><th class="text-end">Subtotal</th></tr>
              </thead>
              <tbody>
                <tr v-for="item in order.items" :key="item.id">
                  <td>{{ item.product?.name }}</td><td>{{ item.quantity }}</td><td class="text-end">${{ item.price }}</td><td class="text-end">${{ (item.price * item.quantity).toFixed(2) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-between align-items-center pt-2 border-top">
            <div class="d-flex gap-2">
              <router-link :to="`/orders/${order.id}`" class="btn btn-outline-primary"><i class="bi bi-eye me-1"></i>View Details</router-link>
              <button class="btn btn-outline-success" :disabled="reordering === order.id" @click="reorder(order.id)">
                <i :class="reordering === order.id ? 'bi-arrow-repeat spinner' : 'bi-arrow-counterclockwise'" class="me-1"></i>
                {{ reordering === order.id ? 'Adding...' : 'Reorder' }}
              </button>
            </div>
            <h6 class="mb-0 text-primary fw-bold">Total: ${{ order.total }}</h6>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.spinner { animation: spin 1s linear infinite; display: inline-block; }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.btn-outline-success:disabled { opacity: 0.7; cursor: not-allowed; }
</style>
