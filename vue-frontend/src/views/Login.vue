<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const auth = useAuth()
const form = ref({ email: '', password: '' })
const showPassword = ref(false)
const error = ref('')
const loading = ref(false)

async function login() {
  error.value = ''
  loading.value = true
  try {
    await auth.handleLogin(form.value)
    if (auth.isAdmin.value) {
      router.push('/admin')
    } else {
      router.push('/')
    }
  } catch (e) {
    error.value = e.response?.data?.message || 'Login failed'
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
            <i class="bi bi-shop fs-1 text-primary"></i>
            <h3 class="mt-2 fw-bold">Welcome Back</h3>
            <p class="text-muted small">Sign in to your account</p>
          </div>
          <p v-if="error" class="text-danger text-center small">
            <i class="bi bi-exclamation-circle me-1"></i>{{ error }}
          </p>
          <form @submit.prevent="login">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input v-model="form.email" type="email" class="form-control" placeholder="you@example.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input v-model="form.password" :type="showPassword ? 'text' : 'password'" class="form-control" placeholder="Enter your password" required>
                <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword">
                  <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" :disabled="loading">
              <i v-if="loading" class="bi bi-hourglass-split me-2"></i>
              <i v-else class="bi bi-box-arrow-in-right me-2"></i>
              {{ loading ? 'Signing in...' : 'Sign In' }}
            </button>
          </form>
          <p class="text-center mt-3 mb-0">
            <span class="text-muted small">Don't have an account?</span>
            <router-link to="/register" class="small ms-1">Register</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
