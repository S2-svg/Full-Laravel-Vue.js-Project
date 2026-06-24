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

function getPasswordStrength(pw) {
  let score = 0
  if (pw.length >= 8) score++
  if (pw.length >= 12) score++
  if (/[A-Z]/.test(pw)) score++
  if (/[0-9]/.test(pw)) score++
  if (/[^A-Za-z0-9]/.test(pw)) score++
  return Math.min(score, 4)
}

function getStrengthClass(bar, pw) {
  const strength = getPasswordStrength(pw)
  if (pw.length === 0) return 'strength-empty'
  return bar <= strength ? `strength-active-${strength}` : 'strength-inactive'
}

function getStrengthColor(pw) {
  const strength = getPasswordStrength(pw)
  const colors = ['', '#f43f5e', '#f59e0b', '#10b981', '#10b981']
  return colors[strength] || '#94a3b8'
}

function getStrengthLabel(pw) {
  const strength = getPasswordStrength(pw)
  const labels = ['', 'Weak', 'Fair', 'Good', 'Strong']
  return labels[strength] || ''
}
</script>

<template>
  <LoadingSpinner v-if="loading" />
  <div v-else class="fade-in-up">
    <div class="section-header">
      <span class="header-icon"><i class="bi bi-person"></i></span>
      <h2>My Profile</h2>
      <span class="header-line"></span>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <h5 class="mb-3 fw-semibold">
              <i class="bi bi-pencil me-2 text-primary"></i>Profile Information
            </h5>
            <form @submit.prevent="updateProfile">
              <div class="mb-3">
                <label for="profile-name" class="form-label">Name</label>
                <input v-model="profile.name" id="profile-name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="profile-email" class="form-label">Email</label>
                <input v-model="profile.email" type="email" id="profile-email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="profile-phone" class="form-label">Phone</label>
                <input v-model="profile.phone" id="profile-phone" class="form-control" placeholder="+1 (555) 000-0000">
              </div>
              <div class="mb-4">
                <label for="profile-address" class="form-label">Address</label>
                <textarea v-model="profile.address" id="profile-address" class="form-control" rows="2" placeholder="Your shipping address"></textarea>
              </div>
              <button type="submit" class="btn btn-primary w-100" :disabled="profileLoading">
                <i v-if="profileLoading" class="bi bi-hourglass-split me-1"></i>
                <i v-else class="bi bi-check-lg me-1"></i>
                {{ profileLoading ? 'Saving...' : 'Update Profile' }}
              </button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
              <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(168,85,247,0.12)); color: var(--color-primary); font-size: 18px; flex-shrink: 0;">
                <i class="bi bi-shield-lock"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-0" style="font-size: 16px;">Change Password</h5>
                <p class="text-muted mb-0" style="font-size: 13px;">Update your account password</p>
              </div>
            </div>
            <form @submit.prevent="changePassword">
              <div class="mb-3">
                <label for="current-password" class="form-label small fw-semibold" style="color: var(--color-text);">
                  <i class="bi bi-lock me-1 text-muted"></i>Current Password
                </label>
                <div class="password-input-group">
                  <input
                    v-model="passwordForm.current_password"
                    :type="showCurrent ? 'text' : 'password'"
                    id="current-password"
                    class="form-control password-input"
                    placeholder="Enter current password"
                    required
                  >
                  <button
                    type="button"
                    class="password-toggle-btn"
                    @click="showCurrent = !showCurrent"
                    :title="showCurrent ? 'Hide password' : 'Show password'"
                  >
                    <i :class="showCurrent ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                  </button>
                </div>
              </div>
              <div class="mb-3">
                <label for="new-password" class="form-label small fw-semibold" style="color: var(--color-text);">
                  <i class="bi bi-key me-1 text-muted"></i>New Password
                </label>
                <div class="password-input-group">
                  <input
                    v-model="passwordForm.new_password"
                    :type="showNew ? 'text' : 'password'"
                    id="new-password"
                    class="form-control password-input"
                    placeholder="Min. 8 characters"
                    required
                    minlength="8"
                  >
                  <button
                    type="button"
                    class="password-toggle-btn"
                    @click="showNew = !showNew"
                    :title="showNew ? 'Hide password' : 'Show password'"
                  >
                    <i :class="showNew ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                  </button>
                </div>
                <div class="mt-2 d-flex gap-1">
                  <div
                    v-for="bar in 4"
                    :key="bar"
                    class="password-strength-bar"
                    :class="getStrengthClass(bar, passwordForm.new_password)"
                  ></div>
                  <span v-if="passwordForm.new_password.length > 0" class="small ms-2 fw-medium" :style="{ color: getStrengthColor(passwordForm.new_password) }">
                    {{ getStrengthLabel(passwordForm.new_password) }}
                  </span>
                </div>
              </div>
              <div class="mb-4">
                <label for="confirm-password" class="form-label small fw-semibold" style="color: var(--color-text);">
                  <i class="bi bi-check-circle me-1 text-muted"></i>Confirm New Password
                </label>
                <div class="password-input-group">
                  <input
                    v-model="passwordForm.new_password_confirmation"
                    :type="showConfirm ? 'text' : 'password'"
                    id="confirm-password"
                    class="form-control password-input"
                    placeholder="Re-enter new password"
                    :class="{ 'is-invalid': passwordForm.new_password_confirmation && passwordForm.new_password_confirmation !== passwordForm.new_password }"
                    required
                  >
                  <button
                    type="button"
                    class="password-toggle-btn"
                    @click="showConfirm = !showConfirm"
                    :title="showConfirm ? 'Hide password' : 'Show password'"
                  >
                    <i :class="showConfirm ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                  </button>
                </div>
                <small v-if="passwordForm.new_password_confirmation && passwordForm.new_password_confirmation !== passwordForm.new_password" class="text-danger d-flex align-items-center gap-1 mt-1">
                  <i class="bi bi-exclamation-circle"></i>Passwords do not match
                </small>
              </div>
              <button type="submit" class="btn btn-primary w-100" :disabled="passLoading || !passwordForm.new_password || !passwordForm.new_password_confirmation || passwordForm.new_password_confirmation !== passwordForm.new_password">
                <i v-if="passLoading" class="bi bi-hourglass-split me-1"></i>
                <i v-else class="bi bi-key me-1"></i>
                {{ passLoading ? 'Changing...' : 'Change Password' }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h5 class="mb-1 fw-semibold">
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
  </div>
</template>

<style scoped>
.login-wrapper {
  max-width: 440px;
  margin: 3rem auto;
}

.login-brand h1 {
  font-weight: 700;
  font-size: 24px;
}

.login-brand p {
  color: #64748b;
  font-size: 14px;
}

.login-card {
  border-radius: var(--radius-lg);
  padding: 2.25rem;
}
</style>
