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
  <div class="login-wrapper">
    <div class="login-brand text-center mb-4">
      <div class="login-brand-icon">
        <i class="bi bi-shop"></i>
      </div>
      <h1>Welcome Back</h1>
      <p class="text-muted">Sign in to your GlobalStore account</p>
    </div>

    <div class="login-card">
      <p v-if="error" class="error-alert" role="alert">
        <i class="bi bi-exclamation-circle me-1"></i>{{ error }}
      </p>
      <form @submit.prevent="login">
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            class="form-control"
            placeholder="you@example.com"
            required
            autofocus
          >
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group-custom">
            <input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              id="password"
              class="form-control"
              placeholder="••••••••"
              required
            >
            <button
              type="button"
              class="toggle-password"
              data-target="password"
              aria-label="Toggle password"
            >
              <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
            </button>
          </div>
        </div>
        <button type="submit" class="btn-login w-100">
          <i v-if="loading" class="bi bi-hourglass-split me-2"></i>
          <i v-else class="bi bi-box-arrow-in-right me-2"></i>
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>
      <p class="footer-text mt-3">
        Don't have an account?
        <router-link to="/register">Create one</router-link>
      </p>
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
