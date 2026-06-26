<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'

const auth = useAuthStore()
const cart = useCartStore()

onMounted(() => {
  cart.fetchCount()
})
</script>

<template>
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
      <router-link class="navbar-brand d-flex align-items-center" to="/">
        <span class="brand-icon me-2">
          <i class="bi bi-shop"></i>
        </span>
        <span class="brand-text">GlobalStore</span>
      </router-link>

      <button
        class="navbar-toggler border-0"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto gap-1">
          <li class="nav-item">
            <router-link class="nav-link" to="/">
              <i class="bi bi-house me-1"></i>Home
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/products">
              <i class="bi bi-grid me-1"></i>Products
            </router-link>
          </li>
        </ul>

        <ul class="navbar-nav align-items-lg-center gap-1">
          <template v-if="auth.isLoggedIn">
            <li class="nav-item">
              <router-link class="nav-link" to="/wishlist">
                <i class="bi bi-heart me-1"></i>Wishlist
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link position-relative" to="/cart">
                <i class="bi bi-cart3 me-1"></i>Cart
                <span v-if="cart.count > 0" class="cart-badge">
                  {{ cart.count > 99 ? '99+' : cart.count }}
                </span>
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/orders">
                <i class="bi bi-box me-1"></i>Orders
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/profile">
                <i class="bi bi-person me-1"></i>Profile
              </router-link>
            </li>
          </template>
          <template v-else>
            <li class="nav-item">
              <router-link class="nav-link" to="/login">
                <i class="bi bi-box-arrow-in-right me-1"></i>Login
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link nav-register" to="/register">
                <i class="bi bi-person-plus me-1"></i>Register
              </router-link>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>
