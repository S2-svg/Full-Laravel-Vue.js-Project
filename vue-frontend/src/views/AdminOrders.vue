<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'

const orders = ref([])
const loading = ref(true)

onMounted(fetchOrders)

async function fetchOrders() {
  loading.value = true
  try {
    const res = await api.get('/admin/orders')
    orders.value = res.data
  } catch {
    // ignore
  } finally {
    loading.value = false
  }
}

const statusBadge = (s) => ({
  'bg-success': s === 'completed',
  'bg-danger': s === 'cancelled',
  'bg-warning text-dark': s === 'pending' || s === 'processing',
})
</script>

<template>
  <div>
    <div class="d-flex align-items-center mb-4">
      <h4 class="mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Orders</h4>
    </div>

    <div v-if="loading" class="text-center py-4">
      <div class="spinner-border"></div>
    </div>
    <div v-else-if="orders.length === 0" class="text-center py-4 text-muted">
      <i class="bi bi-receipt fs-1 d-block mb-2"></i>
      <p>No orders yet.</p>
    </div>
    <div v-else class="table-responsive">
      <table class="table table-hover bg-white rounded shadow-sm">
        <thead class="table-light">
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="o in orders" :key="o.id">
            <td class="fw-medium">#{{ o.order_number }}</td>
            <td>{{ o.user?.name }}</td>
            <td>{{ o.items?.length ?? 0 }}</td>
            <td>${{ o.total }}</td>
            <td><span class="badge" :class="statusBadge(o.status)">{{ o.status }}</span></td>
            <td class="small text-muted">{{ new Date(o.created_at).toLocaleDateString() }}</td>
            <td class="text-end">
              <router-link :to="`/admin/orders/${o.id}`" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i>
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
