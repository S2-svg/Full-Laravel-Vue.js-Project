<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import StarRating from '../components/StarRating.vue'
import { useToast } from '../composables/useToast'
import { useAuth } from '../composables/useAuth'
import { useCartStore } from '../stores/cart'

const route = useRoute()
const router = useRouter()
const auth = useAuth()
const toast = useToast()
const cart = useCartStore()

const product = ref(null)
const reviews = ref([])
const reviewForm = ref({ rating: 5, comment: '' })
const reviewError = ref('')
const loading = ref(true)
const addingCart = ref(false)
const addingWishlist = ref(false)

onMounted(async () => {
  try {
    const [prodRes, revRes] = await Promise.all([
      api.get(`/products/${route.params.id}`),
      api.get(`/products/${route.params.id}/reviews`),
    ])
    product.value = prodRes.data
    reviews.value = revRes.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})

async function addToWishlist() {
  if (!auth.isLoggedIn) return router.push('/login')
  addingWishlist.value = true
  try {
    await api.post('/wishlists', { product_id: product.value.id })
    toast.success('Added to wishlist!')
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error adding to wishlist')
  } finally {
    addingWishlist.value = false
  }
}

async function addToCart() {
  if (!auth.isLoggedIn) return router.push('/login')
  addingCart.value = true
  try {
    await api.post('/carts', { product_id: product.value.id, quantity: 1 })
    toast.success('Added to cart!')
    cart.increment()
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error adding to cart')
  } finally {
    addingCart.value = false
  }
}

async function submitReview() {
  if (!auth.isLoggedIn) return router.push('/login')
  reviewError.value = ''
  try {
    const res = await api.post('/reviews', {
      product_id: product.value.id,
      rating: reviewForm.value.rating,
      comment: reviewForm.value.comment,
    })
    reviews.value.unshift(res.data)
    reviewForm.value = { rating: 5, comment: '' }
    toast.success('Review submitted!')
  } catch (e) {
    reviewError.value = e.response?.data?.message || 'Error submitting review'
  }
}
</script>

<template>
  <LoadingSpinner v-if="loading" />
  <div v-else-if="product">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><router-link to="/">Home</router-link></li>
        <li class="breadcrumb-item"><router-link to="/products">Products</router-link></li>
        <li class="breadcrumb-item active">{{ product.name }}</li>
      </ol>
    </nav>

    <div class="row mb-5">
      <div class="col-md-6">
        <img
          v-if="product.image"
          :src="`/storage/${product.image}`"
          class="img-fluid rounded shadow-sm"
          alt=""
          style="max-height: 450px; width: 100%; object-fit: cover;"
        />
        <div
          v-else
          class="bg-secondary bg-opacity-10 text-muted d-flex align-items-center justify-content-center rounded"
          style="height: 400px"
        >
          <i class="bi bi-image fs-1"></i>
        </div>
      </div>
      <div class="col-md-6">
        <p class="text-muted small mb-1">
          <i class="bi bi-tag me-1"></i>{{ product.category?.name }}
        </p>
        <h1 class="fw-bold">{{ product.name }}</h1>
        <h3 class="text-primary fw-bold mb-3">${{ product.price }}</h3>
        <p class="mb-2">
          <i class="bi bi-box me-1"></i>Stock:
          <span :class="product.stock > 0 ? 'text-success' : 'text-danger'">
            {{ product.stock > 0 ? 'In Stock (' + product.stock + ')' : 'Out of Stock' }}
          </span>
        </p>
        <p class="text-muted">{{ product.description }}</p>
        <div class="d-flex gap-2 mt-4" v-if="!auth.isAdmin">
          <button
            class="btn btn-primary"
            :disabled="addingCart"
            @click="addToCart"
          >
            <i v-if="addingCart" class="bi bi-hourglass-split me-1"></i>
            <i v-else class="bi bi-cart-plus me-1"></i>
            {{ addingCart ? 'Adding...' : 'Add to Cart' }}
          </button>
          <button
            class="btn btn-outline-danger"
            :disabled="addingWishlist"
            @click="addToWishlist"
          >
            <i v-if="addingWishlist" class="bi bi-hourglass-split me-1"></i>
            <i v-else class="bi bi-heart me-1"></i>
            {{ addingWishlist ? 'Adding...' : 'Add to Wishlist' }}
          </button>
        </div>
      </div>
    </div>

    <hr />
    <h3 class="mb-3">
      <i class="bi bi-star me-2 text-warning"></i>Reviews
    </h3>

    <div v-if="auth.isLoggedIn && !auth.isAdmin" class="card p-3 mb-4 border-0 shadow-sm">
      <h5>Write a Review</h5>
      <p v-if="reviewError" class="text-danger small">{{ reviewError }}</p>
      <div class="mb-2">
        <select v-model="reviewForm.rating" class="form-select w-auto">
          <option v-for="i in 5" :key="i" :value="i">{{ i }} Star{{ i > 1 ? 's' : '' }}</option>
        </select>
      </div>
      <textarea
        v-model="reviewForm.comment"
        class="form-control mb-2"
        rows="3"
        placeholder="Share your thoughts about this product..."
      ></textarea>
      <button class="btn btn-primary btn-sm" @click="submitReview">
        <i class="bi bi-send me-1"></i>Submit Review
      </button>
    </div>
    <div v-else-if="auth.isLoggedIn && auth.isAdmin" class="alert alert-warning">
      <i class="bi bi-shield-lock me-1"></i>Admins cannot write reviews.
    </div>
    <div v-else class="alert alert-info">
      <i class="bi bi-info-circle me-1"></i>
      <router-link to="/login">Login</router-link> to write a review.
    </div>

    <div v-if="reviews.length === 0" class="text-center py-4 text-muted">
      <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
      <p>No reviews yet. Be the first to review!</p>
    </div>
    <div v-for="review in reviews" :key="review.id" class="card mb-2 p-3 border-0 shadow-sm">
      <div class="d-flex justify-content-between align-items-center">
        <strong><i class="bi bi-person-circle me-1"></i>{{ review.user?.name }}</strong>
        <StarRating :rating="review.rating" />
      </div>
      <p class="mb-0 mt-2">{{ review.comment }}</p>
    </div>
  </div>
  <div v-else class="text-center py-5">
    <i class="bi bi-exclamation-circle fs-1 text-muted d-block mb-3"></i>
    <h5 class="text-muted">Product not found</h5>
    <router-link to="/products" class="btn btn-primary mt-2">Browse Products</router-link>
  </div>
</template>
