<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const router = useRouter()
const auth = useAuth()
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const showPassword = ref(false)
const showConfirmPassword = ref(false)
const loading = ref(false)
const errors = ref({})

const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

const isValidEmail = computed(() => !form.value.email || emailPattern.test(form.value.email))
const passwordsMatch = computed(() => !form.value.password_confirmation || form.value.password === form.value.password_confirmation)
const isValidForm = computed(() =>
  form.value.name &&
  form.value.email &&
  isValidEmail.value &&
  form.value.password &&
  form.value.password.length >= 8 &&
  form.value.password_confirmation &&
  form.value.password === form.value.password_confirmation
)

// ── Password strength ──
function getPasswordStrength(pw) {
  if (!pw) return 0
  let score = 0
  if (pw.length >= 8) score++
  if (pw.length >= 12) score++
  if (/[A-Z]/.test(pw)) score++
  if (/[0-9]/.test(pw)) score++
  if (/[^A-Za-z0-9]/.test(pw)) score++
  return Math.min(score, 4)
}

const strength = computed(() => getPasswordStrength(form.value.password))
const strengthColors = ['', '#f43f5e', '#f59e0b', '#10b981', '#10b981']
const strengthLabels = ['', 'Weak', 'Fair', 'Good', 'Strong']
const strengthColor = computed(() => strengthColors[strength.value] || '#94a3b8')
const strengthLabel = computed(() => strengthLabels[strength.value] || '')

function getStrengthClass(bar) {
  if (!form.value.password) return 'strength-empty'
  return bar <= strength.value ? `strength-active-${strength.value}` : 'strength-inactive'
}

// ── Validation ──
function validate() {
  const errs = {}
  if (!form.value.name.trim()) errs.name = 'Full name is required'
  if (!form.value.email) errs.email = 'Email is required'
  else if (!emailPattern.test(form.value.email)) errs.email = 'Please enter a valid email'
  if (!form.value.password) errs.password = 'Password is required'
  else if (form.value.password.length < 8) errs.password = 'Password must be at least 8 characters'
  if (!form.value.password_confirmation) errs.password_confirmation = 'Please confirm your password'
  else if (form.value.password !== form.value.password_confirmation) errs.password_confirmation = 'Passwords do not match'
  errors.value = errs
  return Object.keys(errs).length === 0
}

async function register() {
  errors.value = {}
  if (!validate()) return
  loading.value = true
  try {
    await auth.handleRegister(form.value)
    router.push('/')
  } catch (e) {
    const serverErrors = e.response?.data?.errors
    if (serverErrors) {
      const mapped = {}
      for (const [key, msgs] of Object.entries(serverErrors)) {
        mapped[key] = msgs.join(', ')
      }
      errors.value = mapped
    } else {
      errors.value._form = e.response?.data?.message || 'Registration failed. Please try again.'
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
        <i class="bi bi-person-plus"></i>
      </div>
      <h1>Create Account</h1>
      <p class="text-muted">Join GlobalStore and start shopping today</p>
    </div>

    <div class="login-card card border-0 shadow-sm">
      <div class="card-body p-4">
        <!-- Form-level error -->
        <Transition name="fade">
          <div v-if="errors._form" class="error-alert" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>{{ errors._form }}
          </div>
        </Transition>

        <form @submit.prevent="register" novalidate>
          <!-- Full Name -->
          <div class="mb-3">
            <label for="name" class="form-label d-flex align-items-center gap-1">
              <i class="bi bi-person text-muted"></i> Full Name
            </label>
            <div class="input-with-icon">
              <input
                v-model="form.name"
                type="text"
                id="name"
                class="form-control"
                :class="{ 'is-invalid': errors.name }"
                placeholder="Your full name"
                required
                @input="clearFieldError('name')"
              >
            </div>
            <Transition name="fade">
              <small v-if="errors.name" class="text-danger d-flex align-items-center gap-1 mt-1">
                <i class="bi bi-exclamation-circle"></i>{{ errors.name }}
              </small>
            </Transition>
          </div>

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
                placeholder="At least 8 characters"
                required
                minlength="8"
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

            <!-- Strength bars -->
            <div v-if="form.password" class="d-flex align-items-center gap-1 mt-2">
              <div
                v-for="bar in 4"
                :key="bar"
                class="password-strength-bar"
                :class="getStrengthClass(bar)"
              ></div>
              <span class="small ms-2 fw-medium" :style="{ color: strengthColor }">
                {{ strengthLabel }}
              </span>
            </div>

            <Transition name="fade">
              <small v-if="errors.password" class="text-danger d-flex align-items-center gap-1 mt-1">
                <i class="bi bi-exclamation-circle"></i>{{ errors.password }}
              </small>
            </Transition>
          </div>

          <!-- Confirm Password -->
          <div class="mb-4">
            <label for="password_confirmation" class="form-label d-flex align-items-center gap-1">
              <i class="bi bi-check-circle text-muted"></i> Confirm Password
            </label>
            <div class="password-input-group">
              <input
                v-model="form.password_confirmation"
                :type="showConfirmPassword ? 'text' : 'password'"
                id="password_confirmation"
                class="form-control password-input"
                :class="{
                  'is-invalid': errors.password_confirmation || (form.password_confirmation && !passwordsMatch),
                  'is-valid': form.password_confirmation && passwordsMatch && !errors.password_confirmation
                }"
                placeholder="Repeat your password"
                required
                @input="clearFieldError('password_confirmation')"
              >
              <button
                type="button"
                class="password-toggle-btn"
                @click="showConfirmPassword = !showConfirmPassword"
                :title="showConfirmPassword ? 'Hide password' : 'Show password'"
                :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'"
              >
                <i :class="showConfirmPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
              </button>
            </div>

            <!-- Live confirm match indicator -->
            <Transition name="fade">
              <small
                v-if="form.password_confirmation && form.password !== form.password_confirmation"
                class="text-danger d-flex align-items-center gap-1 mt-1"
              >
                <i class="bi bi-exclamation-circle"></i>Passwords do not match
              </small>
            </Transition>

            <Transition name="fade">
              <small
                v-if="form.password_confirmation && form.password === form.password_confirmation"
                class="text-success d-flex align-items-center gap-1 mt-1"
              >
                <i class="bi bi-check-circle"></i>Passwords match
              </small>
            </Transition>

            <Transition name="fade">
              <small v-if="errors.password_confirmation" class="text-danger d-flex align-items-center gap-1 mt-1">
                <i class="bi bi-exclamation-circle"></i>{{ errors.password_confirmation }}
              </small>
            </Transition>
          </div>

          <!-- Submit -->
          <button
            type="submit"
            class="btn btn-primary w-100"
            :disabled="loading || !isValidForm"
          >
            <i v-if="loading" class="bi bi-hourglass-split me-2"></i>
            <i v-else class="bi bi-person-plus me-2"></i>
            {{ loading ? 'Creating Account...' : 'Create Account' }}
          </button>
        </form>

        <div class="divider my-4">
          <span class="divider-text">Already have an account?</span>
        </div>

        <router-link to="/login" class="btn btn-outline-primary w-100">
          <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
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

.form-control.is-valid {
  border-color: var(--color-success);
  box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.10);
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
