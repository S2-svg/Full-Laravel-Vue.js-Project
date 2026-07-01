<script setup>
import { ref, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'
import { useWishlistStore } from '../stores/wishlist'
import { useOrderStore } from '../stores/orders'

const route = useRoute()
const auth = useAuthStore()
const cart = useCartStore()
const showNav = ref(false)
const wishlist = useWishlistStore()
const orders = useOrderStore()

// Close mobile menu on route change (browser back/forward)
watch(() => route.path, () => {
  closeNav()
})

function closeNav() {
  showNav.value = false
}

function toggleNav() {
  showNav.value = !showNav.value
}

// Re-fetch cart/wishlist/orders counts whenever auth state changes (login/logout)
watch(() => auth.isLoggedIn, (loggedIn) => {
  if (loggedIn) {
    cart.fetchCount()
    wishlist.fetchCount()
    orders.fetchCount()
  } else {
    cart.reset()
    wishlist.reset()
    orders.reset()
  }
})

onMounted(() => {
  cart.fetchCount()
  wishlist.fetchCount()
  orders.fetchCount()
})
</script>

<template>
  <!-- Mobile backdrop overlay -->
  <transition name="backdrop-fade">
    <div
      v-if="showNav"
      class="nav-backdrop"
      @click="closeNav"
    ></div>
  </transition>

  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <router-link class="navbar-brand d-flex align-items-center" to="/" @click="closeNav">
        <span class="brand-icon me-2">
          <i class="bi bi-shop"></i>
        </span>
        <span class="brand-text">GlobalStore</span>
      </router-link>

      <button
        class="navbar-toggler border-0"
        :class="{ 'toggler-open': showNav }"
        type="button"
        @click="toggleNav"
        aria-label="Toggle navigation"
        :aria-expanded="showNav"
      >
        <div class="hamburger-lines">
          <span class="hamburger-line line-top"></span>
          <span class="hamburger-line line-middle"></span>
          <span class="hamburger-line line-bottom"></span>
        </div>
      </button>

      <transition name="nav-slide">
        <div
          v-show="showNav"
          class="navbar-collapse"
          id="navbarNav"
        >
          <ul class="navbar-nav me-auto gap-1">
            <li class="nav-item" @click="closeNav">
              <router-link class="nav-link" to="/">
                <i class="bi bi-house me-1"></i>Home
              </router-link>
            </li>
            <li class="nav-item" @click="closeNav">
              <router-link class="nav-link" to="/products">
                <i class="bi bi-grid me-1"></i>Products
              </router-link>
            </li>
          </ul>

          <ul class="navbar-nav align-items-lg-center gap-1">
            <template v-if="auth.isLoggedIn">
              <li class="nav-item" @click="closeNav">
                <router-link class="nav-link position-relative" to="/wishlist">
                  <i class="bi bi-heart me-1"></i>Wishlist
                  <span v-if="wishlist.count > 0" class="cart-badge">
                    {{ wishlist.count > 99 ? '99+' : wishlist.count }}
                  </span>
                </router-link>
              </li>
              <li class="nav-item" @click="closeNav">
                <router-link class="nav-link position-relative" to="/cart">
                  <i class="bi bi-cart3 me-1"></i>Cart
                  <span v-if="cart.count > 0" class="cart-badge">
                    {{ cart.count > 99 ? '99+' : cart.count }}
                  </span>
                </router-link>
              </li>
              <li class="nav-item" @click="closeNav">
                <router-link class="nav-link position-relative" to="/orders">
                  <i class="bi bi-box me-1"></i>Orders
                  <span v-if="orders.count > 0" class="cart-badge">
                    {{ orders.count > 99 ? '99+' : orders.count }}
                  </span>
                </router-link>
              </li>
              <li class="nav-item" @click="closeNav">
                <router-link class="nav-link d-flex align-items-center gap-1" to="/profile">
                  <span v-if="auth.user?.avatar_url" class="nav-avatar-wrapper">
                    <img :src="auth.user.avatar_url" class="nav-avatar" alt="" />
                  </span>
                  <i v-else class="bi bi-person me-1"></i>
                  Profile
                </router-link>
              </li>
            </template>
            <template v-else>
              <li class="nav-item" @click="closeNav">
                <router-link class="nav-link" to="/login">
                  <i class="bi bi-box-arrow-in-right me-1"></i>Login
                </router-link>
              </li>
              <li class="nav-item" @click="closeNav">
                <router-link class="nav-link nav-register" to="/register">
                  <i class="bi bi-person-plus me-1"></i>Register
                </router-link>
              </li>
            </template>
          </ul>
        </div>
      </transition>
    </div>
  </nav>
</template>

<style scoped>
/* ─── Backdrop ─── */
.nav-backdrop {
  position: fixed;
  inset: 0;
  z-index: 1020;
  background: rgba(15, 23, 42, 0.4);
  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
}

.backdrop-fade-enter-active,
.backdrop-fade-leave-active {
  transition: opacity 0.25s ease;
}

.backdrop-fade-enter-from,
.backdrop-fade-leave-to {
  opacity: 0;
}

/* ─── Animated Hamburger ─── */
.navbar-toggler {
  position: relative;
  z-index: 1031;
  width: 40px;
  height: 40px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  outline: none !important;
  box-shadow: none !important;
}

.hamburger-lines {
  width: 22px;
  height: 18px;
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.hamburger-line {
  display: block;
  width: 100%;
  height: 2.5px;
  border-radius: 3px;
  background: var(--color-text-secondary);
  transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  transform-origin: center;
}

.toggler-open .line-top {
  transform: translateY(7.75px) rotate(45deg);
  background: var(--color-primary);
}

.toggler-open .line-middle {
  opacity: 0;
  transform: scaleX(0);
}

.toggler-open .line-bottom {
  transform: translateY(-7.75px) rotate(-45deg);
  background: var(--color-primary);
}

/* ─── Mobile slide animation ─── */
.nav-slide-enter-active {
  animation: navSlideDown 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.nav-slide-leave-active {
  animation: navSlideUp 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

@keyframes navSlideDown {
  from {
    opacity: 0;
    transform: translateY(-8px) scaleY(0.97);
  }
  to {
    opacity: 1;
    transform: translateY(0) scaleY(1);
  }
}

@keyframes navSlideUp {
  from {
    opacity: 1;
    transform: translateY(0) scaleY(1);
  }
  to {
    opacity: 0;
    transform: translateY(-8px) scaleY(0.97);
  }
}

/* ─── Mobile menu styling ─── */
@media (max-width: 991.98px) {
  .navbar-collapse {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.97);
    backdrop-filter: blur(20px) saturate(140%);
    -webkit-backdrop-filter: blur(20px) saturate(140%);
    border-radius: 0 0 var(--radius-md) var(--radius-md);
    padding: 0.75rem 1rem;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    border-top: 1px solid rgba(15, 23, 42, 0.05);
  }

  .navbar-nav {
    width: 100%;
  }

  .navbar-nav .nav-link {
    padding: 12px 16px !important;
    border-radius: var(--radius-sm);
    width: 100%;
    font-size: 15px;
  }

  .navbar-nav .nav-link .cart-badge {
    position: static;
    display: inline-flex;
    margin-left: 8px;
    vertical-align: middle;
  }

  .navbar-nav .nav-register {
    text-align: center;
    margin-top: 4px;
  }
}
</style>
