<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import EmptyState from '../components/EmptyState.vue'

const orders = ref([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await api.get('/orders')
    orders.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

const statusBadge = (status) => ({
  'bg-success': status === 'completed',
  'bg-danger': status === 'cancelled',
  'bg-warning text-dark': status === 'pending' || status === 'processing',
})
</script>

<template>
  <h1 class="mb-4">
    <i class="bi bi-box me-2 text-primary"></i>My Orders
  </h1>
  <LoadingSpinner v-if="loading" />
  <EmptyState
    v-else-if="orders.length === 0"
    icon="bi-box"
    title="No orders yet"
    message="You haven't placed any orders yet."
    linkTo="/products"
    linkText="Start Shopping"
  />
  <div v-else>
    <div v-for="order in orders" :key="order.id" class="card mb-3 border-0 shadow-sm">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-medium">
          <i class="bi bi-receipt me-1"></i>Order #{{ order.order_number }}
        </span>
        <span class="badge" :class="statusBadge(order.status)">{{ order.status }}</span>
      </div>
      <div class="card-body">
        <p class="text-muted small mb-3">
          <i class="bi bi-calendar me-1"></i>Placed: {{ new Date(order.created_at).toLocaleDateString() }}
        </p>
        <table class="table table-sm mb-3">
          <thead class="table-light">
            <tr>
              <th>Product</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in order.items" :key="item.id">
              <td>{{ item.product?.name }}</td>
              <td>{{ item.quantity }}</td>
              <td>${{ item.price }}</td>
              <td>${{ (item.price * item.quantity).toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center">
          <router-link
            :to="`/orders/${order.id}`"
            class="btn btn-sm btn-outline-primary"
          >
            <i class="bi bi-eye me-1"></i>View Details
          </router-link>
          <h6 class="mb-0 text-primary fw-bold">Total: ${{ order.total }}</h6>
        </div>
      </div>
    </div>
  </div>
</template>
