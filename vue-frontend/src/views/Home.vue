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

  <div class="section-header fade-in-up mb-4">
    <div class="d-flex align-items-center gap-2">
      <span class="header-icon-modern"><i class="bi bi-grid-1x2-fill"></i></span>
      <h2 class="fw-bold tracking-tight mb-0">Browse Categories</h2>
    </div>
    <span class="header-line"></span>
  </div>

  <div class="d-flex flex-nowrap overflow-x-auto gap-3 pb-3 mb-4 hide-scrollbar smooth-scroll">
    <div v-for="cat in categories" :key="cat.id" class="flex-shrink-0">
      <router-link :to="`/products?category_id=${cat.id}`" class="text-decoration-none">
        <div class="modern-category-chip">
          <span class="modern-chip-bg"></span>
          <div class="modern-chip-content">
            <span class="category-dot"></span>
            <span class="chip-text">{{ cat.name }}</span>
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
  <LoadingSpinner v-if="loading" />
  <div v-else class="row mb-2">
    <div v-for="product in featuredProducts" :key="product.id" class="col-6 col-md-3 mb-4">
      <ProductCard :product="product" />
    </div>
    <div class="section-header d-flex justify-content-between align-items-center fade-in-up">
      <div class="d-flex align-items-center gap-2">
      </div>
      <router-link to="/products" class="btn btn-outline-primary btn-sm rounded-pill px-3">
        See More <i class="bi bi-arrow-right ms-1"></i>
      </router-link>
    </div>
  </div>

  <div class="features-wrapper position-relative" style="margin-top: 16px; margin-bottom: 56px; padding: 48px 0 40px;">
    <!-- Decorative background blobs -->
    <div class="position-absolute"
      style="top: -40px; right: -60px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(99,102,241,0.10), transparent 70%); border-radius: 50%; pointer-events: none; z-index: 0;">
    </div>
    <div class="position-absolute"
      style="bottom: -30px; left: -80px; width: 240px; height: 240px; background: radial-gradient(circle, rgba(168,85,247,0.08), transparent 70%); border-radius: 50%; pointer-events: none; z-index: 0;">
    </div>

    <div class="section-header fade-in-up position-relative">
      <span class="header-icon"><i class="bi bi-info-circle"></i></span>
      <h2>Why Shop With Us</h2>
      <span class="header-line"></span>
    </div>

    <p class="text-center text-muted mb-5 fade-in-up position-relative"
      style="z-index: 1; max-width: 580px; margin-left: auto; margin-right: auto; line-height: 1.7;">
      GlobalStore is your trusted destination for quality products at unbeatable prices.
      We curate every item with care, ensuring you get the best value with every purchase.
    </p>

    <div class="row g-4 position-relative">
      <div class="col-md-6 col-lg-3" v-for="(feature, i) in features" :key="i" :style="{ animationDelay: `${i * 0.12}s` }">
        <div class="feature-card h-100">
          <div class="card-body text-center p-4">
            <div class="feature-icon-wrap mb-3"
              :style="`background: ${feature.iconBg}; color: ${feature.iconColor}; box-shadow: 0 8px 24px ${feature.shadow};`">
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
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
              style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end)); font-size: 14px;">
              {{ review.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <div>
              <strong>{{ review.user?.name || 'User' }}</strong>
              <div class="text-muted small" style="font-size: 12px;">
                on <router-link :to="`/products/${review.product?.id}`" class="fw-semibold">{{ review.product?.name ||
                  'Product' }}</router-link>
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
/* Core Scroll Mechanics */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.smooth-scroll {
  scroll-behavior: smooth;
  -webkit-overflow-scrolling: touch;
}

/* Modernized Header Icon Style */
.header-icon-modern {
  font-size: 1.2rem;
  color: var(--color-primary, #6366f1);
  background: rgba(99, 102, 241, 0.1);
  padding: 8px 12px;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.tracking-tight {
  letter-spacing: -0.5px;
}

/* Modern Category Pill Layout */
.modern-category-chip {
  position: relative;
  padding: 10px 24px;
  border-radius: 30px;
  background: #ffffff;
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
}

.modern-chip-content {
  position: relative;
  z-index: 2;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* A sleek little colored dot instead of a giant repeating icon */
.category-dot {
  width: 6px;
  height: 6px;
  background-color: var(--color-primary, #6366f1);
  border-radius: 50%;
  transition: transform 0.3s ease;
}

.chip-text {
  font-size: 14px;
  font-weight: 600;
  color: #2d3748;
  transition: color 0.3s ease;
}

/* Hover Background Slide Effect */
.modern-chip-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(168, 85, 247, 0.04));
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: 1;
}

/* Interactive Hover States */
.modern-category-chip:hover {
  transform: translateY(-3px);
  border-color: rgba(99, 102, 241, 0.2);
  box-shadow: 0 8px 20px rgba(99, 102, 241, 0.08);
}

.modern-category-chip:hover .modern-chip-bg {
  opacity: 1;
}

.modern-category-chip:hover .chip-text {
  color: var(--color-primary, #6366f1);
}

.modern-category-chip:hover .category-dot {
  transform: scale(1.5);
  background-color: #a855f7; /* Shifting dot hue on hover */
}
</style>
