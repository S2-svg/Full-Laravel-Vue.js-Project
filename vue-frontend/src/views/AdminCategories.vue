<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import { useToast } from '../composables/useToast'

const toast = useToast()
const categories = ref([])
const loading = ref(true)
const showForm = ref(false)
const editing = ref(null)
const form = ref({ name: '', description: '' })
const submitting = ref(false)

onMounted(fetchCategories)

async function fetchCategories() {
  loading.value = true
  try {
    const res = await api.get('/admin/categories')
    categories.value = res.data
  } catch {
    toast.error('Failed to load categories')
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editing.value = null
  form.value = { name: '', description: '' }
  showForm.value = true
}

function openEdit(cat) {
  editing.value = cat.id
  form.value = { name: cat.name, description: cat.description || '' }
  showForm.value = true
}

function cancelForm() {
  showForm.value = false
  editing.value = null
}

async function submitForm() {
  submitting.value = true
  try {
    if (editing.value) {
      await api.put(`/admin/categories/${editing.value}`, form.value)
      toast.success('Category updated')
    } else {
      await api.post('/admin/categories', form.value)
      toast.success('Category created')
    }
    showForm.value = false
    editing.value = null
    await fetchCategories()
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error saving category')
  } finally {
    submitting.value = false
  }
}

async function deleteCategory(id) {
  if (!confirm('Delete this category?')) return
  try {
    await api.delete(`/admin/categories/${id}`)
    toast.success('Category deleted')
    await fetchCategories()
  } catch {
    toast.error('Error deleting category')
  }
}
</script>

<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0"><i class="bi bi-tags me-2 text-primary"></i>Categories</h4>
      <button class="btn btn-primary btn-sm" @click="openCreate">
        <i class="bi bi-plus me-1"></i>Add Category
      </button>
    </div>

    <div v-if="showForm" class="card border-0 shadow-sm p-3 mb-4">
      <h6>{{ editing ? 'Edit Category' : 'Create Category' }}</h6>
      <form @submit.prevent="submitForm">
        <div class="row g-3">
          <div class="col-md-6">
            <input v-model="form.name" class="form-control" placeholder="Category name" required />
          </div>
          <div class="col-md-6">
            <input v-model="form.description" class="form-control" placeholder="Description (optional)" />
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-sm me-2" :disabled="submitting">
              {{ submitting ? 'Saving...' : 'Save' }}
            </button>
            <button type="button" class="btn btn-secondary btn-sm" @click="cancelForm">Cancel</button>
          </div>
        </div>
      </form>
    </div>

    <div v-if="loading" class="text-center py-4">
      <div class="spinner-border"></div>
    </div>
    <div v-else-if="categories.length === 0" class="text-center py-4 text-muted">
      <i class="bi bi-tags fs-1 d-block mb-2"></i>
      <p>No categories yet.</p>
    </div>
    <div v-else class="table-responsive">
      <table class="table table-hover bg-white rounded shadow-sm">
        <thead class="table-light">
          <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Products</th>
            <th>Created</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cat in categories" :key="cat.id">
            <td class="fw-medium">{{ cat.name }}</td>
            <td><code>{{ cat.slug }}</code></td>
            <td><span class="badge bg-info">{{ cat.products_count ?? 0 }}</span></td>
            <td class="text-muted small">{{ new Date(cat.created_at).toLocaleDateString() }}</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-primary me-1" @click="openEdit(cat)">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" @click="deleteCategory(cat.id)">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
