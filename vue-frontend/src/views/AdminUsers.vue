<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'

const users = ref([])
const loading = ref(true)

onMounted(fetchUsers)

async function fetchUsers() {
  loading.value = true
  try {
    const res = await api.get('/admin/users')
    users.value = res.data
  } catch {
    // ignore
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <div class="d-flex align-items-center mb-4">
      <h4 class="mb-0"><i class="bi bi-people me-2 text-primary"></i>Users</h4>
    </div>

    <div v-if="loading" class="text-center py-4">
      <div class="spinner-border"></div>
    </div>
    <div v-else class="table-responsive">
      <table class="table table-hover bg-white rounded shadow-sm">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Orders</th>
            <th>Joined</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users" :key="u.id">
            <td class="fw-medium">
              <i class="bi bi-person-circle me-1"></i>{{ u.name }}
            </td>
            <td>{{ u.email }}</td>
            <td>
              <span :class="'badge ' + (u.role === 'admin' ? 'bg-warning text-dark' : 'bg-info')">
                {{ u.role }}
              </span>
            </td>
            <td>{{ u.orders_count ?? 0 }}</td>
            <td class="small text-muted">{{ new Date(u.created_at).toLocaleDateString() }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
