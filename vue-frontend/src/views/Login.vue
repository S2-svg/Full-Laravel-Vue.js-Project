<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const auth = useAuth()
const form = ref({ email: '', password: '' })
const showPassword = ref(false)
const loading = ref(false)
const errors = ref({})

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
const isValidEmail = computed(() => !form.value.email || emailPattern.test(form.value.email))
const isValidForm = computed(() => form.value.email && form.value.password && isValidEmail.value)

function validate() {
  const errs = {}
  if (!form.value.email) errs.email = 'Email is required'
  else if (!emailPattern.test(form.value.email)) errs.email = 'Please enter a valid email'
  if (!form.value.password) errs.password = 'Password is required'
  errors.value = errs
  return Object.keys(errs).length === 0
}

async function login() {
  errors.value = {}
  if (!validate()) return
  loading.value = true
  try {
    await auth.handleLogin(form.value)
    if (auth.isAdmin.value) {
      router.push('/admin')
    } else {
      router.push('/')
    }
  } catch (e) {
    const msg = e.response?.data?.message
    if (msg) {
      errors.value._form = msg
    } else {
      errors.value._form = 'Login failed. Please check your credentials.'
    }
  } finally {
    loading.value = false
  }
}

function clearFieldError(field) {
  if (errors.value[field]) {
    delete errors.value[field]
  }
}
</script>

<template>
  <div class="login-wrapper fade-in-up">
    <div class="login-brand text-center mb-4">
      <div class="login-brand-icon">
        <i class="bi bi-shop"></i>
      </div>
      <h1>Welcome Back</h1>
      <p class="text-muted">Sign in to your GlobalStore account</p>
    </div>

    <div class="login-card card border-0 shadow-sm">
      <div class="card-body p-4">
        <!-- Form-level error -->
        <Transition name="fade">
          <div v-if="errors._form" class="error-alert" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>{{ errors._form }}
          </div>
        </Transition>

        <form @submit.prevent="login" novalidate>
          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label d-flex align-items-center gap-1">
              <i class="bi bi-envelope text-muted"></i> Email address
            </label>
            <input
              v-model="form.email"
              type="email"
              id="email"
              class="form-control"
              :class="{ 'is-invalid': errors.email }"
              placeholder="you@example.com"
              required
              autofocus
              @input="clearFieldError('email')"
            >
            <Transition name="fade">
              <small v-if="errors.email" class="text-danger d-flex align-items-center gap-1 mt-1">
                <i class="bi bi-exclamation-circle"></i>{{ errors.email }}
              </small>
            </Transition>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label for="password" class="form-label d-flex align-items-center gap-1">
              <i class="bi bi-lock text-muted"></i> Password
            </label>
            <div class="password-input-group">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                id="password"
                class="form-control password-input"
                :class="{ 'is-invalid': errors.password }"
                placeholder="Enter your password"
                required
                @input="clearFieldError('password')"
              >
              <button
                type="button"
                class="password-toggle-btn"
                @click="showPassword = !showPassword"
                :title="showPassword ? 'Hide password' : 'Show password'"
                :aria-label="showPassword ? 'Hide password' : 'Show password'"
              >
                <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
              </button>
            </div>
            <Transition name="fade">
              <small v-if="errors.password" class="text-danger d-flex align-items-center gap-1 mt-1">
                <i class="bi bi-exclamation-circle"></i>{{ errors.password }}
              </small>
            </Transition>
          </div>

          <!-- Forgot password (placeholder - feature coming soon) -->
          <div class="d-flex justify-content-end mb-4">
            <span class="forgot-link small text-muted" style="cursor:default;">
              Forgot password?
            </span>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            class="btn btn-primary w-100"
            :disabled="loading || !isValidForm"
          >
            <i v-if="loading" class="bi bi-hourglass-split me-2"></i>
            <i v-else class="bi bi-box-arrow-in-right me-2"></i>
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <div class="divider my-4">
          <span class="divider-text">New here?</span>
        </div>

        <router-link to="/register" class="btn btn-outline-primary w-100">
          <i class="bi bi-person-plus me-2"></i>Create an Account
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.login-wrapper {
  max-width: 440px;
  margin: 3rem auto;
}

.login-brand-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 1rem;
  border-radius: 18px;
  background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end));
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 28px;
  box-shadow: 0 12px 28px rgba(99, 102, 241, 0.3);
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
  border-radius: var(--radius-md);
  background: var(--color-surface);
}

.error-alert {
  background: rgba(244, 63, 94, 0.08);
  color: #e11d48;
  border: 1px solid rgba(244, 63, 94, 0.15);
  padding: 12px 16px;
  border-radius: var(--radius-sm);
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  gap: 6px;
}

.error-alert i {
  font-size: 16px;
  flex-shrink: 0;
}

.form-control.is-invalid {
  border-color: var(--color-danger);
  box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.10);
}

.form-control.is-invalid:focus {
  border-color: var(--color-danger);
  box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.15);
}

.forgot-link {
  color: var(--color-primary);
  text-decoration: none;
  font-weight: 500;
  transition: color var(--transition-fast);
}

.forgot-link:hover {
  color: var(--color-accent);
  text-decoration: underline;
}

.divider {
  display: flex;
  align-items: center;
  text-align: center;
  gap: 12px;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--color-border);
}

.divider-text {
  color: var(--color-text-secondary);
  font-size: 13px;
  font-weight: 500;
  white-space: nowrap;
}

@media (max-width: 480px) {
  .login-wrapper {
    margin: 1.5rem auto;
    padding: 0 12px;
  }
}
</style>
