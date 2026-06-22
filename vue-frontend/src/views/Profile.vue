<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import { useToast } from '../composables/useToast'
import { useAuthStore } from '../stores/auth'

const toast = useToast()
const auth = useAuthStore()
const profile = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
})
const passwordForm = ref({ current_password: '', new_password: '', new_password_confirmation: '' })
const showCurrent = ref(false)
const showNew = ref(false)
const showConfirm = ref(false)
const profileLoading = ref(false)
const passLoading = ref(false)
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await api.get('/profile')
    profile.value = res.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

async function handleLogout() {
  auth.logout()
}

async function updateProfile() {
  profileLoading.value = true
  try {
    const res = await api.put('/profile', profile.value)
    profile.value = res.data
    auth.user = res.data
    localStorage.setItem('user', JSON.stringify(res.data))
    toast.success('Profile updated successfully')
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error updating profile')
  } finally {
    profileLoading.value = false
  }
}

async function changePassword() {
  passLoading.value = true
  try {
    await api.put('/change-password', passwordForm.value)
    toast.success('Password changed successfully')
    passwordForm.value = { current_password: '', new_password: '', new_password_confirmation: '' }
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error changing password')
  } finally {
    passLoading.value = false
  }
}
</script>

<template>
  <LoadingSpinner v-if="loading" />
  <div v-else>
    <h1 class="mb-4">
      <i class="bi bi-person me-2 text-primary"></i>My Profile
    </h1>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4">
          <h5 class="mb-3">
            <i class="bi bi-pencil me-2 text-primary"></i>Profile Information
          </h5>
          <form @submit.prevent="updateProfile">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input v-model="profile.name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input v-model="profile.email" type="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input v-model="profile.phone" class="form-control" placeholder="+1 (555) 000-0000">
            </div>
            <div class="mb-3">
              <label class="form-label">Address</label>
              <textarea v-model="profile.address" class="form-control" rows="2" placeholder="Your shipping address"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" :disabled="profileLoading">
              <i v-if="profileLoading" class="bi bi-hourglass-split me-1"></i>
              <i v-else class="bi bi-check-lg me-1"></i>
              {{ profileLoading ? 'Saving...' : 'Update Profile' }}
            </button>
          </form>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4">
          <h5 class="mb-3">
            <i class="bi bi-shield-lock me-2 text-primary"></i>Change Password
          </h5>
          <form @submit.prevent="changePassword">
            <div class="mb-3">
              <label class="form-label">Current Password</label>
              <div class="input-group">
                <input v-model="passwordForm.current_password" :type="showCurrent ? 'text' : 'password'" class="form-control" required>
                <button class="btn btn-outline-secondary" type="button" @click="showCurrent = !showCurrent">
                  <i :class="showCurrent ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <div class="input-group">
                <input v-model="passwordForm.new_password" :type="showNew ? 'text' : 'password'" class="form-control" required minlength="8">
                <button class="btn btn-outline-secondary" type="button" @click="showNew = !showNew">
                  <i :class="showNew ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm New Password</label>
              <div class="input-group">
                <input v-model="passwordForm.new_password_confirmation" :type="showConfirm ? 'text' : 'password'" class="form-control" required>
                <button class="btn btn-outline-secondary" type="button" @click="showConfirm = !showConfirm">
                  <i :class="showConfirm ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
              </div>
            </div>
            <button type="submit" class="btn btn-primary" :disabled="passLoading">
              <i v-if="passLoading" class="bi bi-hourglass-split me-1"></i>
              <i v-else class="bi bi-key me-1"></i>
              {{ passLoading ? 'Changing...' : 'Change Password' }}
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm p-4">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <h5 class="mb-1">
                <i class="bi bi-box-arrow-right me-2 text-danger"></i>Logout
              </h5>
              <p class="text-muted small mb-0">Sign out of your account</p>
            </div>
            <button class="btn btn-outline-danger" @click="handleLogout">
              <i class="bi bi-box-arrow-right me-1"></i>Logout
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
