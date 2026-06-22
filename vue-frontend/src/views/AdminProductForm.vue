<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import { useToast } from '../composables/useToast'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const isEdit = !!route.params.id

const form = ref({
  category_id: '',
  name: '',
  description: '',
  price: '',
  stock: '',
})
const categories = ref([])
const image = ref(null)
const imagePreview = ref('')
const submitting = ref(false)
const loading = ref(true)

onMounted(async () => {
  try {
    const [catRes] = await Promise.all([api.get('/categories')])
    categories.value = catRes.data
    if (isEdit) {
      const prodRes = await api.get(`/admin/products/${route.params.id}`)
      const p = prodRes.data
      form.value = {
        category_id: p.category_id,
        name: p.name,
        description: p.description || '',
        price: p.price,
        stock: p.stock,
      }
      if (p.image) imagePreview.value = `/storage/${p.image}`
    }
  } catch {
    toast.error('Error loading data')
  } finally {
    loading.value = false
  }
})

function onImageChange(e) {
  const file = e.target.files[0]
  if (!file) return
  image.value = file
  imagePreview.value = URL.createObjectURL(file)
}

async function submitForm() {
  submitting.value = true
  try {
    const fd = new FormData()
    fd.append('category_id', form.value.category_id)
    fd.append('name', form.value.name)
    fd.append('description', form.value.description)
    fd.append('price', form.value.price)
    fd.append('stock', form.value.stock)
    if (image.value) {
      fd.append('image', image.value)
    }

    if (isEdit) {
      fd.append('_method', 'PUT')
      await api.post(`/admin/products/${route.params.id}`, fd)
      toast.success('Product updated')
    } else {
      await api.post('/admin/products', fd)
      toast.success('Product created')
    }
    router.push('/admin/products')
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error saving product')
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div v-if="loading" class="text-center py-4">
    <div class="spinner-border"></div>
  </div>
  <div v-else>
    <div class="d-flex align-items-center mb-4">
      <router-link to="/admin/products" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i>
      </router-link>
      <h4 class="mb-0">{{ isEdit ? 'Edit Product' : 'Create Product' }}</h4>
    </div>

    <div class="card border-0 shadow-sm p-4">
      <form @submit.prevent="submitForm">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Name</label>
            <input v-model="form.name" class="form-control" required />
          </div>
          <div class="col-md-3">
            <label class="form-label">Category</label>
            <select v-model="form.category_id" class="form-select" required>
              <option value="">Select...</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Price ($)</label>
            <input v-model="form.price" type="number" step="0.01" min="0" class="form-control" required />
          </div>
          <div class="col-md-3">
            <label class="form-label">Stock</label>
            <input v-model="form.stock" type="number" min="0" class="form-control" required />
          </div>
          <div class="col-md-12">
            <label class="form-label">Description</label>
            <textarea v-model="form.description" class="form-control" rows="3"></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Image</label>
            <input type="file" class="form-control" accept="image/jpeg,image/png,image/jpg" @change="onImageChange" />
            <small class="text-muted">Allowed: jpeg, png, jpg (max 2MB)</small>
          </div>
          <div class="col-md-6" v-if="imagePreview">
            <img :src="imagePreview" style="max-height: 150px;" class="rounded mt-2" />
          </div>
          <div class="col-12 mt-4">
            <button type="submit" class="btn btn-primary" :disabled="submitting">
              {{ submitting ? 'Saving...' : (isEdit ? 'Update Product' : 'Create Product') }}
            </button>
            <router-link to="/admin/products" class="btn btn-secondary ms-2">Cancel</router-link>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
