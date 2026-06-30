<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import ProductCard from '../components/ProductCard.vue'
import StarRating from '../components/StarRating.vue'
import StoreLocation from '../components/StoreLocation.vue'
import { useCategoriesStore } from '../stores/categories'

const featuredProducts = ref([])
const reviews = ref([])
const productsReady = ref(false)
const reviewsReady = ref(false)
const categoriesStore = useCategoriesStore()

const features = [
  { icon: 'bi bi-truck', title: 'Fast Delivery', description: 'Get your order delivered quickly and safely to your doorstep.', iconBg: 'linear-gradient(135deg, rgba(99,102,241,0.12), rgba(99,102,241,0.06))', iconColor: 'var(--color-primary)', shadow: 'rgba(99,102,241,0.25)' },
  { icon: 'bi bi-shield-check', title: 'Secure Payment', description: '100% secure payment with SSL encryption support.', iconBg: 'linear-gradient(135deg, rgba(16,185,129,0.12), rgba(16,185,129,0.06))', iconColor: 'var(--color-success)', shadow: 'rgba(16,185,129,0.25)' },
  { icon: 'bi bi-arrow-repeat', title: 'Easy Returns', description: 'Hassle-free returns within 30 days of purchase.', iconBg: 'linear-gradient(135deg, rgba(245,158,11,0.12), rgba(245,158,11,0.06))', iconColor: 'var(--color-warning)', shadow: 'rgba(245,158,11,0.25)' },
  { icon: 'bi bi-headset', title: '24/7 Support', description: 'Our support team is ready to help you anytime.', iconBg: 'linear-gradient(135deg, rgba(244,63,94,0.12), rgba(244,63,94,0.06))', iconColor: 'var(--color-danger)', shadow: 'rgba(244,63,94,0.25)' },
]

onMounted(async () => {
  categoriesStore.fetch()
  api.get('/products?per_page=8').then(r => { featuredProducts.value = r.data.data || []; productsReady.value = true })
  api.get('/reviews').then(r => { reviews.value = r.data.slice(0, 2); reviewsReady.value = true })
})
</script>

<template>
  <section class="hero-section fade-in-up">
    <h1 class="fw-bold">Discover What's Next</h1>
    <p class="lead mb-4">Handpicked products, fast delivery, and an experience built for you.</p>
    <router-link to="/products" class="btn btn-light btn-lg px-5 fw-bold">
      <i class="bi bi-bag me-2"></i>Shop Now
    </router-link>
  </section>

  <div class="section-header fade-in-up">
    <span class="header-icon"><i class="bi bi-tag"></i></span>
    <h2>Categories</h2>
    <span class="header-line"></span>
  </div>
  <div class="row mb-5">
    <div v-for="cat in categoriesStore.items" :key="cat.id" class="col-6 col-md-3 mb-3">
      <router-link :to="`/products?category_id=${cat.id}`" class="text-decoration-none">
        <div class="card category-card h-100 border-0 shadow-sm">
          <div class="card-body d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-tag text-primary"></i>
            <span class="fw-semibold">{{ cat.name }}</span>
          </div>
        </div>
      </router-link>
    </div>
  </div>

  <div class="section-header fade-in-up">
    <span class="header-icon"><i class="bi bi-star"></i></span>
    <h2>Featured Products</h2>
    <span class="header-line"></span>
  </div>
  <div class="row">
    <div v-for="product in featuredProducts" :key="product.id" class="col-6 col-md-3 mb-4">
      <ProductCard :product="product" />
    </div>
  </div>

  <div class="features-wrapper">
    <div class="blob blob-top-right"></div>
    <div class="blob blob-bottom-left"></div>

    <div class="section-header fade-in-up position-relative">
      <span class="header-icon"><i class="bi bi-info-circle"></i></span>
      <h2>Why Shop With Us</h2>
      <span class="header-line"></span>
    </div>

    <p class="features-intro fade-in-up">
      GlobalStore is your trusted destination for quality products at unbeatable prices.
      We curate every item with care, ensuring you get the best value with every purchase.
    </p>

    <div class="row g-4 position-relative">
      <div class="col-md-6 col-lg-3" v-for="(feature, i) in features" :key="i" :style="{ animationDelay: `${i * 0.12}s` }">
        <div class="feature-card h-100">
          <div class="card-body text-center p-4">
            <div class="feature-icon-wrap mb-3" :style="{ background: feature.iconBg, color: feature.iconColor, boxShadow: `0 8px 24px ${feature.shadow}` }">
              <i :class="feature.icon" class="feature-icon"></i>
            </div>
            <h6 class="fw-bold mb-2 feature-title">{{ feature.title }}</h6>
            <p class="text-muted small mb-0 feature-desc">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section-header fade-in-up reviews-header">
    <span class="header-icon"><i class="bi bi-chat-dots"></i></span>
    <h2>Latest Reviews</h2>
    <span class="header-line"></span>
  </div>
  <div v-if="reviews.length === 0 && !reviewsReady" class="text-center py-4 text-muted">
    <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
    <p>Loading reviews...</p>
  </div>
  <div v-else-if="reviews.length === 0" class="text-center py-4 text-muted">
    <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
    <p>No reviews yet.</p>
  </div>
  <div v-else class="row mb-5">
    <div v-for="review in reviews" :key="review.id" class="col-md-6 mb-3">
      <div class="card review-card p-3 h-100 border-0 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <div class="review-avatar">{{ review.user?.name?.charAt(0)?.toUpperCase() || 'U' }}</div>
            <div>
              <strong>{{ review.user?.name || 'User' }}</strong>
              <div class="text-muted small review-meta">
                on <router-link :to="`/products/${review.product?.id}`" class="fw-semibold">{{ review.product?.name || 'Product' }}</router-link>
              </div>
            </div>
          </div>
          <StarRating :rating="review.rating" />
        </div>
        <p class="mb-0 mt-2 text-muted review-comment">{{ review.comment }}</p>
        <p class="text-muted small mb-0 mt-2">
          <i class="bi bi-calendar me-1"></i>{{ new Date(review.created_at).toLocaleDateString() }}
        </p>
      </div>
    </div>
  </div>

  <StoreLocation class="mt-5" />
</template>

<style scoped>
.features-wrapper { margin-top: 56px; margin-bottom: 56px; padding: 48px 0 40px; }
.features-intro { text-align: center; color: var(--color-text-secondary); margin-bottom: 2.5rem; max-width: 580px; margin-left: auto; margin-right: auto; line-height: 1.7; }
.feature-title { font-size: 16px; }
.feature-desc { line-height: 1.6; }
.reviews-header { margin-top: 48px; }
.review-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end)); flex-shrink: 0; }
.review-meta { font-size: 12px; }
.review-comment { line-height: 1.6; }
.blob { position: absolute; border-radius: 50%; pointer-events: none; z-index: 0; }
.blob-top-right { top: -40px; right: -60px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(99,102,241,0.10), transparent 70%); }
.blob-bottom-left { bottom: -30px; left: -80px; width: 240px; height: 240px; background: radial-gradient(circle, rgba(168,85,247,0.08), transparent 70%); }
</style>
