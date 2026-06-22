<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import LoadingSpinner from '../components/LoadingSpinner.vue'

const route = useRoute()
const router = useRouter()
const order = ref(null)
const loading = ref(true)

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
</script>

<template>
  <LoadingSpinner v-if="loading" />
  <div v-else-if="order">
    <router-link to="/orders" class="btn btn-sm btn-outline-secondary mb-3">
      <i class="bi bi-arrow-left me-1"></i>Back to Orders
    </router-link>
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Order #{{ order.order_number }}</h5>
        <span class="badge" :class="statusBadge(order.status)">{{ order.status }}</span>
      </div>
      <div class="card-body">
        <p class="text-muted">
          Placed: {{ new Date(order.created_at).toLocaleDateString() }}
        </p>
        <table class="table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in order.items" :key="item.id">
              <td>{{ item.product?.name }}</td>
              <td>${{ item.price }}</td>
              <td>{{ item.quantity }}</td>
              <td>${{ (item.price * item.quantity).toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
        <hr />
        <div class="text-end">
          <h4 class="mb-0">Total: ${{ order.total }}</h4>
        </div>
      </div>
    </div>
  </div>
</template>
