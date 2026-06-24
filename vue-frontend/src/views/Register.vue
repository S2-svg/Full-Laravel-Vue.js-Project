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
  <div class="login-wrapper">
    <div class="login-brand text-center mb-4">
      <div class="login-brand-icon">
        <i class="bi bi-person-plus"></i>
      </div>
      <h1>Create Account</h1>
      <p class="text-muted">Join GlobalStore and start shopping today</p>
    </div>

    <div class="login-card">
      <p v-if="error" class="error-alert" role="alert">
        <i class="bi bi-exclamation-circle me-1"></i>{{ error }}
      </p>
      <form @submit.prevent="register">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input v-model="form.name" type="text" id="name" class="form-control" placeholder="Your full name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input v-model="form.email" type="email" id="email" class="form-control" placeholder="you@example.com" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group-custom">
            <input v-model="form.password" :type="showPassword ? 'text' : 'password'" id="password" class="form-control" placeholder="At least 8 characters" required minlength="8">
            <button type="button" class="toggle-password" data-target="password" aria-label="Toggle password">
              <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
            </button>
          </div>
        </div>
        <div class="mb-4">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <div class="input-group-custom">
            <input v-model="form.password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation" class="form-control" placeholder="Repeat your password" required>
            <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Toggle confirm password">
              <i :class="showConfirmPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
            </button>
          </div>
        </div>
        <button type="submit" class="btn-login w-100">
          <i v-if="loading" class="bi bi-hourglass-split me-2"></i>
          <i v-else class="bi bi-person-plus me-2"></i>
          {{ loading ? 'Creating Account...' : 'Create Account' }}
        </button>
      </form>
      <p class="footer-text mt-3">
        Already have an account?
        <router-link to="/login">Sign in</router-link>
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
