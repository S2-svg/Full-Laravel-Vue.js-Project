<script setup>
import { ref, onMounted, computed } from 'vue'
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
const loading = ref(true)
const addingCart = ref(false)
const addingWishlist = ref(false)
const submittingReview = ref(false)
const reviewError = ref('')

const reviewForm = ref({
  rating: 0,
  comment: '',
})

const hoverRating = ref(0)
const canSubmit = computed(() => reviewForm.value.rating > 0 && reviewForm.value.comment.trim().length > 0)

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

function setRating(val) {
  reviewForm.value.rating = val
}

function setHover(val) {
  hoverRating.value = val
}

function clearHover() {
  hoverRating.value = 0
}

async function submitReview() {
  if (!auth.isLoggedIn) return router.push('/login')
  if (!canSubmit.value) return
  submittingReview.value = true
  reviewError.value = ''
  try {
    const res = await api.post('/reviews', {
      product_id: product.value.id,
      rating: reviewForm.value.rating,
      comment: reviewForm.value.comment,
    })
    reviews.value.unshift(res.data)
    reviewForm.value = { rating: 0, comment: '' }
    hoverRating.value = 0
    toast.success('Review submitted!')
  } catch (e) {
    reviewError.value = e.response?.data?.message || 'Error submitting review'
  } finally {
    submittingReview.value = false
  }
}

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
    cart.fetchCount()
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error adding to cart')
  } finally {
    addingCart.value = false
  }
}


</script>

<template>
  <LoadingSpinner v-if="loading" />
  <div v-else-if="product" class="fade-in-up">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><router-link to="/">Home</router-link></li>
        <li class="breadcrumb-item"><router-link to="/products">Products</router-link></li>
        <li class="breadcrumb-item active">{{ product.name }}</li>
      </ol>
    </nav>

    <div class="row g-4 mb-5">
      <div class="col-md-6">
        <div class="position-relative rounded overflow-hidden" style="background: #f8fafc;">
          <img
            v-if="product.image"
            :src="`/storage/${product.image}`"
            class="product-image-main"
            alt=""
          />
          <div
            v-else
            class="bg-light text-muted d-flex align-items-center justify-content-center rounded"
            style="height: 420px"
          >
            <i class="bi bi-image fs-1"></i>
          </div>
          <button
            class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 rounded-circle"
            style="width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center;"
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
          <p class="text-muted small mb-1 fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
            <i class="bi bi-tag me-1"></i>{{ product.category?.name }}
          </p>
          <h1 class="fw-bold mb-2">{{ product.name }}</h1>
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="text-primary fw-bold" style="font-size: 28px;">${{ product.price }}</span>
            <span class="badge rounded-pill" :class="product.stock > 0 ? 'bg-success' : 'bg-danger'">
              {{ product.stock > 0 ? `In Stock (${product.stock})` : 'Out of Stock' }}
            </span>
          </div>
          <p class="text-muted mb-4" style="line-height: 1.7;">{{ product.description }}</p>
          <div class="d-flex gap-2 mt-auto">
            <button
              class="btn btn-primary btn-lg flex-grow-1"
              :disabled="addingCart"
              @click="addToCart"
            >
              <i v-if="addingCart" class="bi bi-hourglass-split me-2"></i>
              <i v-else class="bi bi-cart-plus me-2"></i>
              {{ addingCart ? 'Adding...' : 'Add to Cart' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="row g-4 mb-5">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="section-header">
              <span class="header-icon"><i class="bi bi-star"></i></span>
              <h2 class="mb-0">Customer Reviews</h2>
              <span class="header-line"></span>
            </div>

            <!-- Review Form -->
            <div class="review-form-card mb-4 position-relative overflow-hidden">
              <!-- Decorative accent bar -->
              <div class="position-absolute top-0 start-0 end-0" style="height: 3px; background: linear-gradient(90deg, var(--color-gradient-start), var(--color-gradient-end), transparent); z-index: 1;"></div>
              <!-- Subtle background pattern -->
              <div class="position-absolute" style="top: -60px; right: -60px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(99,102,241,0.06), transparent 70%); border-radius: 50%; pointer-events: none;"></div>

              <div class="p-4 position-relative" style="z-index: 1;">
                <div class="d-flex align-items-center gap-3 mb-3">
                  <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(168,85,247,0.12)); color: var(--color-primary); font-size: 18px; flex-shrink: 0;">
                    <i class="bi bi-pencil-square"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-0" style="font-size: 15px;">
                      {{ auth.isLoggedIn ? 'Write a Review' : 'Log in to write a review' }}
                    </h6>
                    <p class="text-muted mb-0" style="font-size: 13px;">Share your experience with this product</p>
                  </div>
                </div>

                <!-- Interactive Star Rating -->
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3 p-3 rounded-3" style="background: rgba(99,102,241,0.03); border: 1px solid rgba(99,102,241,0.06);">
                  <span class="fw-semibold" style="font-size: 13px; color: var(--color-text-secondary); min-width: 85px;">Your Rating</span>
                  <div class="d-flex align-items-center gap-1">
                    <span
                      v-for="star in 5"
                      :key="star"
                      class="star-input"
                      :class="{
                        'star-active': star <= (hoverRating || reviewForm.rating),
                        'star-inactive': star > (hoverRating || reviewForm.rating),
                      }"
                      @click="auth.isLoggedIn && setRating(star)"
                      @mouseenter="auth.isLoggedIn && setHover(star)"
                      @mouseleave="clearHover"
                      :style="{ cursor: auth.isLoggedIn ? 'pointer' : 'not-allowed' }"
                    >
                      <i
                        :class="star <= (hoverRating || reviewForm.rating) ? 'bi bi-star-fill' : 'bi bi-star'"
                      ></i>
                    </span>
                  </div>
                  <span v-if="reviewForm.rating > 0" class="badge rounded-pill ms-2" style="background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end)); font-weight: 600; font-size: 12px; padding: 5px 14px;">
                    {{ ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent'][reviewForm.rating] }}
                  </span>
                  <span v-else class="small text-muted ms-1">Click a star to rate</span>
                </div>

                <!-- Comment Textarea -->
                <div class="mb-3 review-textarea-wrapper">
                  <textarea
                    class="form-control review-textarea"
                    rows="3"
                    placeholder="Tell others about your experience... What did you like or dislike?"
                    v-model="reviewForm.comment"
                    maxlength="500"
                    :disabled="!auth.isLoggedIn"
                  ></textarea>
                  <div class="d-flex justify-content-end mt-1">
                    <small class="text-muted" style="font-size: 11px;">
                      {{ reviewForm.comment.length }} / 500
                    </small>
                  </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                  <button
                    class="btn btn-primary"
                    :disabled="!canSubmit || submittingReview"
                    @click="submitReview"
                    style="min-width: 160px;"
                  >
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

            <!-- Existing Reviews -->
            <div v-if="reviews.length === 0" class="text-center py-4 text-muted">
              <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
              <p>No reviews yet. Be the first to review!</p>
            </div>
            <div v-for="review in reviews" :key="review.id" class="card review-card mb-2 p-3 border-0 shadow-sm">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <div
                    class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                    style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end)); font-size: 14px;"
                  >
                    {{ review.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                  </div>
                  <strong>{{ review.user?.name || 'User' }}</strong>
                </div>
                <StarRating :rating="review.rating" />
              </div>
              <p class="mb-0 mt-2 text-muted" style="line-height: 1.6;">{{ review.comment }}</p>
              <p class="text-muted small mb-0 mt-2">
                <i class="bi bi-calendar me-1"></i>{{ new Date(review.created_at).toLocaleDateString() }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="text-center py-5">
    <i class="bi bi-exclamation-circle fs-1 text-muted d-block mb-3"></i>
    <h5 class="text-muted">Product not found</h5>
    <router-link to="/products" class="btn btn-primary mt-2">Browse Products</router-link>
  </div>
</template>
