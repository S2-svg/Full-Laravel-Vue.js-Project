<script setup>
const year = new Date().getFullYear()
</script>

<template>
  <footer class="app-footer text-white mt-5 py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <h5 class="fw-bold mb-3">
            <i class="bi bi-shop me-2" style="color: #667eea;"></i>GlobalStore
          </h5>
          <p class="text-white-50 small lh-lg" style="max-width: 280px;">
            Your one-stop shop for amazing products at unbeatable prices. Quality guaranteed, satisfaction ensured.
          </p>
          <div class="d-flex gap-3 mt-3">
            <a href="#" class="text-white-50 fs-5"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white-50 fs-5"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="text-white-50 fs-5"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white-50 fs-5"><i class="bi bi-github"></i></a>
          </div>
        </div>
        <div class="col-md-2 offset-md-1">
          <h6 class="fw-bold mb-3" style="color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-size: 11px;">Shop</h6>
          <ul class="list-unstyled small d-flex flex-column gap-2">
            <li><router-link to="/" class="text-white-50 text-decoration-none nav-link-hover">Home</router-link></li>
            <li><router-link to="/products" class="text-white-50 text-decoration-none nav-link-hover">Products</router-link></li>
            <li><router-link to="/cart" class="text-white-50 text-decoration-none nav-link-hover">Cart</router-link></li>
          </ul>
        </div>
        <div class="col-md-2">
          <h6 class="fw-bold mb-3" style="color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-size: 11px;">Account</h6>
          <ul class="list-unstyled small d-flex flex-column gap-2">
            <li><router-link to="/profile" class="text-white-50 text-decoration-none nav-link-hover">Profile</router-link></li>
            <li><router-link to="/orders" class="text-white-50 text-decoration-none nav-link-hover">Orders</router-link></li>
            <li><router-link to="/wishlist" class="text-white-50 text-decoration-none nav-link-hover">Wishlist</router-link></li>
          </ul>
        </div>
        <div class="col-md-2">
          <h6 class="fw-bold mb-3" style="color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-size: 11px;">Support</h6>
          <ul class="list-unstyled small d-flex flex-column gap-2">
            <li><i class="bi bi-envelope me-2 text-white-50"></i><span class="text-white-50">support@store.com</span></li>
            <li><i class="bi bi-telephone me-2 text-white-50"></i><span class="text-white-50">+1 (555) 123-4567</span></li>
            <li><i class="bi bi-geo-alt me-2 text-white-50"></i><span class="text-white-50">New York, USA</span></li>
          </ul>
        </div>
      </div>
      <hr class="border-white-10 mt-4 mb-3" style="opacity: 0.08;" />
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <p class="text-white-50 small mb-0">&copy; {{ year }} GlobalStore. All rights reserved.</p>
        <div class="small text-white-50">
          <span class="me-3">Privacy Policy</span>
          <span>Terms of Service</span>
        </div>
      </div>
    </div>
  </footer>
</template>
