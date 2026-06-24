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
  <div class="section-header">
    <span class="header-icon"><i class="bi bi-box"></i></span>
    <h2>My Orders</h2>
    <span class="header-line"></span>
  </div>

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
    <div v-for="order in orders" :key="order.id" class="card border-0 shadow-sm mb-3">
      <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <span class="fw-semibold">
          <i class="bi bi-receipt me-1"></i>Order #{{ order.order_number }}
        </span>
        <span class="badge rounded-pill" :class="statusBadge(order.status)">{{ order.status }}</span>
      </div>
      <div class="card-body">
        <p class="text-muted small mb-3">
          <i class="bi bi-calendar me-1"></i>Placed: {{ new Date(order.created_at).toLocaleDateString() }}
        </p>
        <div class="table-responsive">
          <table class="table table-sm mb-3">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th>Qty</th>
                <th class="text-end">Price</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in order.items" :key="item.id">
                <td>{{ item.product?.name }}</td>
                <td>{{ item.quantity }}</td>
                <td class="text-end">${{ item.price }}</td>
                <td class="text-end">${{ (item.price * item.quantity).toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
          <router-link
            :to="`/orders/${order.id}`"
            class="btn btn-outline-primary"
          >
            <i class="bi bi-eye me-1"></i>View Details
          </router-link>
          <h6 class="mb-0 text-primary fw-bold">Total: ${{ order.total }}</h6>
        </div>
      </div>
    </div>
  </div>
</template>
