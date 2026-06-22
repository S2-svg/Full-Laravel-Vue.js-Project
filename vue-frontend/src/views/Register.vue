<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const auth = useAuth()
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const showPassword = ref(false)
const showConfirmPassword = ref(false)
const error = ref('')
const loading = ref(false)

async function register() {
  error.value = ''
  loading.value = true
  try {
    await auth.handleRegister(form.value)
    router.push('/')
  } catch (e) {
    const errors = e.response?.data?.errors
    if (errors) {
      error.value = Object.values(errors).flat().join(', ')
    } else {
      error.value = e.response?.data?.message || 'Registration failed'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <i class="bi bi-person-plus fs-1 text-primary"></i>
            <h3 class="mt-2 fw-bold">Create Account</h3>
            <p class="text-muted small">Join us and start shopping</p>
          </div>
          <p v-if="error" class="text-danger text-center small">
            <i class="bi bi-exclamation-circle me-1"></i>{{ error }}
          </p>
          <form @submit.prevent="register">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input v-model="form.name" class="form-control" placeholder="Your full name" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input v-model="form.email" type="email" class="form-control" placeholder="you@example.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input v-model="form.password" :type="showPassword ? 'text' : 'password'" class="form-control" placeholder="At least 8 characters" required minlength="8">
                <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword">
                  <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm Password</label>
              <div class="input-group">
                <input v-model="form.password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" class="form-control" placeholder="Repeat your password" required>
                <button class="btn btn-outline-secondary" type="button" @click="showConfirmPassword = !showConfirmPassword">
                  <i :class="showConfirmPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" :disabled="loading">
              <i v-if="loading" class="bi bi-hourglass-split me-2"></i>
              <i v-else class="bi bi-person-plus me-2"></i>
              {{ loading ? 'Creating Account...' : 'Create Account' }}
            </button>
          </form>
          <p class="text-center mt-3 mb-0">
            <span class="text-muted small">Already have an account?</span>
            <router-link to="/login" class="small ms-1">Login</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
