<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'
import StarRating from '../components/StarRating.vue'
import { useToast } from '../composables/useToast'
import { useAuth } from '../composables/useAuth'
import { useCartStore } from '../stores/cart'
import { useWishlistStore } from '../stores/wishlist'

const route = useRoute()
const router = useRouter()
const auth = useAuth()
const toast = useToast()
const cart = useCartStore()
const wishlist = useWishlistStore()

const product = ref(null)
const reviews = ref([])
const productReady = ref(false)
const addingCart = ref(false)
const addingWishlist = ref(false)
const submittingReview = ref(false)
const reviewError = ref('')

const reviewForm = ref({ rating: 0, comment: '' })
const hoverRating = ref(0)
const canSubmit = computed(() => reviewForm.value.rating > 0 && reviewForm.value.comment.trim().length > 0)

onMounted(async () => {
  try {
    const [productRes, reviewsRes] = await Promise.all([
      api.get(`/products/${route.params.id}`),
      api.get(`/products/${route.params.id}/reviews`),
    ])
    // API resource wraps response in 'data' key; reviews returns plain array
    product.value = productRes.data.data
    reviews.value = reviewsRes.data
    productReady.value = true
  } catch (e) {
    toast.error('Failed to load product details')
    console.error('Product detail load error:', e)
  }
})

function setRating(val) { reviewForm.value.rating = val }
function setHover(val) { hoverRating.value = val }
function clearHover() { hoverRating.value = 0 }

async function submitReview() {
  if (!auth.isLoggedIn) return router.push('/login')
  if (!canSubmit.value || !product.value) return
  submittingReview.value = true
  reviewError.value = ''
  try {
    const res = await api.post('/reviews', { product_id: product.value.id, rating: reviewForm.value.rating, comment: reviewForm.value.comment })
    reviews.value.unshift(res.data)
    reviewForm.value = { rating: 0, comment: '' }
    hoverRating.value = 0
    toast.success('Review submitted!')
  } catch (e) {
    reviewError.value = e.response?.data?.message || 'Error submitting review'
  } finally { submittingReview.value = false }
}

async function addToWishlist() {
  if (!auth.isLoggedIn) return router.push('/login')
  if (!product.value) {
    toast.error('Product data not loaded yet')
    return
  }
  addingWishlist.value = true
  try {
    await api.post('/wishlists', { product_id: product.value.id })
    toast.success('Added to wishlist!')
    wishlist.fetchCount()
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error adding to wishlist')
  } finally { addingWishlist.value = false }
}

const isOutOfStock = computed(() => product.value && product.value.stock <= 0)

async function addToCart() {
  if (!auth.isLoggedIn) return router.push('/login')
  if (isOutOfStock.value) return
  addingCart.value = true
  try {
    await api.post('/carts', { product_id: product.value.id, quantity: 1 })
    cart.fetchCount()
    router.push('/cart')
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error adding to cart')
  } finally { addingCart.value = false }
}
</script>

<template>
  <div class="fade-in-up">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><router-link to="/">Home</router-link></li>
        <li class="breadcrumb-item"><router-link to="/products">Products</router-link></li>
        <li class="breadcrumb-item active" v-if="product">{{ product.name }}</li>
        <li class="breadcrumb-item active" v-else>Loading...</li>
      </ol>
    </nav>

    <!-- Loading spinner while product loads -->
    <div v-if="!product" class="text-center py-5">
      <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="text-muted">Loading product details...</p>
    </div>

    <template v-if="product">
    <div class="row g-4 mb-5">
      <div class="col-md-6">
        <div class="product-image-wrapper">
          <img
            v-if="product?.image"
            :src="`/storage/${product.image}`"
            class="product-image-main"
            :alt="product?.name || 'Product'"
            loading="lazy"
          />
          <div
            v-else
            class="product-image-main d-flex align-items-center justify-content-center"
            style="padding: 0;"
          >
            <i class="bi bi-image" style="font-size: 3rem; opacity: 0.4;"></i>
          </div>
          <button
            class="btn btn-sm wishlist-btn-overlay position-absolute top-0 end-0 m-2 rounded-circle d-inline-flex align-items-center justify-content-center"
            :disabled="addingWishlist"
            @click="addToWishlist"
            title="Add to wishlist"
          >
            <i class="bi bi-heart"></i>
          </button>
        </div>
      </div>
      <div class="col-md-6 d-flex flex-column">
        <div>
          <p class="text-muted small mb-1 fw-semibold text-uppercase category-label">
            <i class="bi bi-tag me-1"></i>{{ product?.category?.name || '...' }}
          </p>
          <h1 class="fw-bold mb-2">{{ product?.name || 'Loading...' }}</h1>
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center gap-2">
              <span v-if="product?.has_discount" class="text-danger fw-bold price-xl">${{ product.final_price }}</span>
              <span v-else-if="product" class="text-primary fw-bold price-xl">${{ product.price }}</span>
              <span v-else class="text-primary fw-bold price-xl">$--</span>
              <span v-if="product?.has_discount" class="text-muted text-decoration-line-through price-lg">${{ product.price }}</span>
              <span v-if="product?.has_discount" class="badge rounded-pill px-2 py-1 discount-badge">-{{ product.discount_percent }}%</span>
            </div>
            <span v-if="product" class="badge rounded-pill" :class="product.stock > 0 ? 'bg-success' : 'bg-danger'">
              {{ product.stock > 0 ? `In Stock (${product.stock})` : 'Out of Stock' }}
            </span>
          </div>
          <p class="text-muted mb-4 product-desc">{{ product?.description || '' }}</p>
          <div class="d-flex gap-2 mt-auto">
            <button v-if="isOutOfStock" class="btn btn-secondary btn-lg flex-grow-1 btn-disabled" disabled>
              <i class="bi bi-x-circle me-2"></i>Out of Stock
            </button>
            <button v-else class="btn btn-primary btn-lg flex-grow-1" :disabled="addingCart || !product" @click="addToCart">
              <i v-if="addingCart" class="bi bi-hourglass-split me-2"></i>
              <i v-else class="bi bi-cart-plus me-2"></i>
              {{ addingCart ? 'Adding...' : 'Add to Cart' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4 mb-5">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="section-header">
              <span class="header-icon"><i class="bi bi-star"></i></span>
              <h2 class="mb-0">Customer Reviews</h2>
              <span class="header-line"></span>
            </div>

            <div class="review-form-card mb-4 position-relative overflow-hidden">
              <div class="review-accent-bar"></div>
              <div class="review-blob"></div>
              <div class="p-4 position-relative">
                <div class="d-flex align-items-center gap-3 mb-3">
                  <div class="review-icon-circle">
                    <i class="bi bi-pencil-square"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-0 review-section-title">{{ auth.isLoggedIn ? 'Write a Review' : 'Log in to write a review' }}</h6>
                    <p class="text-muted mb-0 review-section-subtitle">Share your experience with this product</p>
                  </div>
                </div>

                <div class="rating-box">
                  <span class="fw-semibold rating-label">Your Rating</span>
                  <div class="d-flex align-items-center gap-1">
                    <span v-for="star in 5" :key="star" class="star-input" :class="{ 'star-active': star <= (hoverRating || reviewForm.rating), 'star-inactive': star > (hoverRating || reviewForm.rating) }" @click="auth.isLoggedIn && setRating(star)" @mouseenter="auth.isLoggedIn && setHover(star)" @mouseleave="clearHover" :style="{ cursor: auth.isLoggedIn ? 'pointer' : 'not-allowed' }">
                      <i :class="star <= (hoverRating || reviewForm.rating) ? 'bi bi-star-fill' : 'bi bi-star'"></i>
                    </span>
                  </div>
                  <span v-if="reviewForm.rating > 0" class="badge rounded-pill ms-2 rating-badge">{{ ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent'][reviewForm.rating] }}</span>
                  <span v-else class="small text-muted ms-1">Click a star to rate</span>
                </div>

                <div class="mb-3 review-textarea-wrapper">
                  <textarea class="form-control review-textarea" rows="3" placeholder="Tell others about your experience... What did you like or dislike?" v-model="reviewForm.comment" maxlength="500" :disabled="!auth.isLoggedIn"></textarea>
                  <div class="d-flex justify-content-end mt-1">
                    <small class="text-muted char-count">{{ reviewForm.comment.length }} / 500</small>
                  </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                  <button class="btn btn-primary btn-submit" :disabled="!canSubmit || submittingReview" @click="submitReview">
                    <i v-if="submittingReview" class="bi bi-hourglass-split me-1"></i>
                    <i v-else class="bi bi-send me-1"></i>
                    {{ submittingReview ? 'Submitting...' : 'Submit Review' }}
                  </button>
                  <transition name="fade">
                    <span v-if="reviewError" class="small d-flex align-items-center gap-1 text-danger fw-medium">
                      <i class="bi bi-exclamation-circle"></i>{{ reviewError }}
                    </span>
                  </transition>
                </div>
              </div>
            </div>

            <div v-if="reviews.length === 0" class="text-center py-4 text-muted">
              <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
              <p>No reviews yet. Be the first to review!</p>
            </div>
            <div v-for="review in reviews" :key="review.id" class="card review-card mb-2 p-3 border-0 shadow-sm">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <div class="review-review-avatar">{{ review.user?.name?.charAt(0)?.toUpperCase() || 'U' }}</div>
                  <strong>{{ review.user?.name || 'User' }}</strong>
                </div>
                <StarRating :rating="review.rating" />
              </div>
              <p class="mb-0 mt-2 text-muted review-comment">{{ review.comment }}</p>
              <p class="text-muted small mb-0 mt-2"><i class="bi bi-calendar me-1"></i>{{ new Date(review.created_at).toLocaleDateString() }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    </template>
  </div>
</template>

<style scoped>
.product-img-wrapper { position: relative; border-radius: var(--radius-md); overflow: hidden; background: #f8fafc; }
.product-img-placeholder { height: 420px; background: var(--color-bg); display: flex; align-items: center; justify-content: center; color: var(--color-text-secondary); border-radius: var(--radius-md); }
.wishlist-btn-circle { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; border: 2px solid var(--color-border); background: var(--color-surface); color: var(--color-danger); transition: all var(--transition-fast); cursor: pointer; }
.wishlist-btn-circle:hover { background: rgba(244, 63, 94, 0.08); border-color: var(--color-danger); }
.category-label { letter-spacing: 0.5px; }
.price-xl { font-size: 28px; }
.price-lg { font-size: 18px; }
.discount-badge { background: #ef4444; font-size: 14px; font-weight: 700; }
.product-desc { line-height: 1.7; }
.btn-disabled { opacity: 0.6; cursor: not-allowed; }
.review-accent-bar { position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--color-gradient-start), var(--color-gradient-end), transparent); z-index: 1; }
.review-blob { position: absolute; top: -60px; right: -60px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(99,102,241,0.06), transparent 70%); border-radius: 50%; pointer-events: none; }
.review-icon-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(168,85,247,0.12)); color: var(--color-primary); font-size: 18px; flex-shrink: 0; }
.review-section-title { font-size: 15px; }
.review-section-subtitle { font-size: 13px; }
.rating-box { display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; padding: 1rem; border-radius: var(--radius-sm); background: rgba(99,102,241,0.03); border: 1px solid rgba(99,102,241,0.06); }
.rating-label { font-size: 13px; color: var(--color-text-secondary); min-width: 85px; }
.rating-badge { background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end)); font-weight: 600; font-size: 12px; padding: 5px 14px; }
.char-count { font-size: 11px; }
.btn-submit { min-width: 160px; }
.review-review-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end)); flex-shrink: 0; }
.review-comment { line-height: 1.6; }
</style>
