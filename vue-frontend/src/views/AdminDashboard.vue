<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'

const stats = ref(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await api.get('/admin/stats')
    stats.value = res.data
  } catch {
    // ignore
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div v-if="loading" class="text-center py-4">
    <div class="spinner-border"></div>
  </div>
  <div v-else>
    <h4 class="mb-4"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</h4>

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <router-link to="/admin/users" class="text-decoration-none">
          <div class="card border-0 shadow-sm p-3 text-center h-100">
            <i class="bi bi-people fs-1 text-primary mb-2"></i>
            <h3 class="fw-bold mb-1">{{ stats?.users ?? 0 }}</h3>
            <p class="text-muted small mb-0">Users</p>
          </div>
        </router-link>
      </div>
      <div class="col-md-3">
        <router-link to="/admin/products" class="text-decoration-none">
          <div class="card border-0 shadow-sm p-3 text-center h-100">
            <i class="bi bi-box fs-1 text-success mb-2"></i>
            <h3 class="fw-bold mb-1">{{ stats?.products ?? 0 }}</h3>
            <p class="text-muted small mb-0">Products</p>
          </div>
        </router-link>
      </div>
      <div class="col-md-3">
        <router-link to="/admin/categories" class="text-decoration-none">
          <div class="card border-0 shadow-sm p-3 text-center h-100">
            <i class="bi bi-tags fs-1 text-warning mb-2"></i>
            <h3 class="fw-bold mb-1">{{ stats?.categories ?? 0 }}</h3>
            <p class="text-muted small mb-0">Categories</p>
          </div>
        </router-link>
      </div>
      <div class="col-md-3">
        <router-link to="/admin/orders" class="text-decoration-none">
          <div class="card border-0 shadow-sm p-3 text-center h-100">
            <i class="bi bi-receipt fs-1 text-info mb-2"></i>
            <h3 class="fw-bold mb-1">{{ stats?.orders ?? 0 }}</h3>
            <p class="text-muted small mb-0">Orders</p>
          </div>
        </router-link>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Orders</h6>
          </div>
          <div class="card-body p-0">
            <div v-if="stats?.recent_orders?.length" class="list-group list-group-flush">
              <router-link
                v-for="order in stats.recent_orders"
                :key="order.id"
                :to="`/admin/orders/${order.id}`"
                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
              >
                <div>
                  <small class="text-muted">#{{ order.order_number }}</small>
                  <br />
                  <small class="text-muted">{{ new Date(order.created_at).toLocaleDateString() }}</small>
                </div>
                <span class="badge" :class="{
                  'bg-success': order.status === 'completed',
                  'bg-danger': order.status === 'cancelled',
                  'bg-warning text-dark': order.status === 'pending' || order.status === 'processing',
                }">{{ order.status }}</span>
              </router-link>
            </div>
            <div v-else class="p-3 text-center text-muted small">No recent orders</div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-lightning me-2 text-primary"></i>Quick Actions</h6>
          </div>
          <div class="card-body d-flex flex-column gap-2">
            <router-link to="/admin/products/create" class="btn btn-outline-primary text-start">
              <i class="bi bi-plus-circle me-2"></i>Add New Product
            </router-link>
            <router-link to="/admin/categories" class="btn btn-outline-primary text-start">
              <i class="bi bi-plus-circle me-2"></i>Manage Categories
            </router-link>
            <router-link to="/admin/orders" class="btn btn-outline-primary text-start">
              <i class="bi bi-eye me-2"></i>View Orders
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
