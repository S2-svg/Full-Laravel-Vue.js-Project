<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import { useToast } from '../composables/useToast'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const order = ref(null)
const loading = ref(true)
const updating = ref(false)
const selectedStatus = ref('')

onMounted(async () => {
  try {
    const res = await api.get(`/admin/orders/${route.params.id}`)
    order.value = res.data
    selectedStatus.value = res.data.status
  } catch {
    toast.error('Order not found')
    router.push('/admin/orders')
  } finally {
    loading.value = false
  }
})

async function updateStatus() {
  updating.value = true
  try {
    const res = await api.put(`/admin/orders/${route.params.id}/status`, { status: selectedStatus.value })
    order.value = res.data
    toast.success('Status updated')
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error updating status')
  } finally {
    updating.value = false
  }
}

const statusBadge = (s) => ({
  'bg-success': s === 'completed',
  'bg-danger': s === 'cancelled',
  'bg-warning text-dark': s === 'pending' || s === 'processing',
})
</script>

<template>
  <div v-if="loading" class="text-center py-4">
    <div class="spinner-border"></div>
  </div>
  <div v-else-if="order">
    <div class="d-flex align-items-center mb-4">
      <router-link to="/admin/orders" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i>
      </router-link>
      <h4 class="mb-0">Order #{{ order.order_number }}</h4>
      <span class="badge ms-3 fs-6" :class="statusBadge(order.status)">{{ order.status }}</span>
    </div>

    <div class="row g-4">
      <div class="col-md-8">
        <div class="card border-0 shadow-sm p-3">
          <h6 class="mb-3">Order Items</h6>
          <table class="table">
            <thead class="table-light">
              <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
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
            <h5 class="mb-0">Total: ${{ order.total }}</h5>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 mb-3">
          <h6>Customer</h6>
          <p class="mb-1"><strong>{{ order.user?.name }}</strong></p>
          <p class="mb-0 text-muted small">{{ order.user?.email }}</p>
        </div>
        <div class="card border-0 shadow-sm p-3">
          <h6>Update Status</h6>
          <select v-model="selectedStatus" class="form-select mb-2">
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <button class="btn btn-primary w-100" @click="updateStatus" :disabled="updating">
            {{ updating ? 'Updating...' : 'Update Status' }}
          </button>
        </div>
        <div class="card border-0 shadow-sm p-3 mt-3">
          <h6>Order Details</h6>
          <p class="mb-1 small"><strong>Date:</strong> {{ new Date(order.created_at).toLocaleString() }}</p>
          <p class="mb-0 small"><strong>Order #:</strong> {{ order.order_number }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
