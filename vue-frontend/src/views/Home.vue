<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import ProductCard from '../components/ProductCard.vue'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import StarRating from '../components/StarRating.vue'
import StoreLocation from '../components/StoreLocation.vue'

const featuredProducts = ref([])
const categories = ref([])
const reviews = ref([])
const loading = ref(true)

const features = [
  {
    icon: 'bi bi-truck',
    title: 'Fast Delivery',
    description: 'Get your order delivered quickly and safely to your doorstep.',
    iconBg: 'linear-gradient(135deg, rgba(99,102,241,0.12), rgba(99,102,241,0.06))',
    iconColor: 'var(--color-primary)',
    shadow: 'rgba(99,102,241,0.25)',
  },
  {
    icon: 'bi bi-shield-check',
    title: 'Secure Payment',
    description: '100% secure payment with SSL encryption support.',
    iconBg: 'linear-gradient(135deg, rgba(16,185,129,0.12), rgba(16,185,129,0.06))',
    iconColor: 'var(--color-success)',
    shadow: 'rgba(16,185,129,0.25)',
  },
  {
    icon: 'bi bi-arrow-repeat',
    title: 'Easy Returns',
    description: 'Hassle-free returns within 30 days of purchase.',
    iconBg: 'linear-gradient(135deg, rgba(245,158,11,0.12), rgba(245,158,11,0.06))',
    iconColor: 'var(--color-warning)',
    shadow: 'rgba(245,158,11,0.25)',
  },
  {
    icon: 'bi bi-headset',
    title: '24/7 Support',
    description: 'Our support team is ready to help you anytime.',
    iconBg: 'linear-gradient(135deg, rgba(244,63,94,0.12), rgba(244,63,94,0.06))',
    iconColor: 'var(--color-danger)',
    shadow: 'rgba(244,63,94,0.25)',
  },
]

onMounted(async () => {
  try {
    const [prodRes, catRes, revRes] = await Promise.all([
      api.get('/products?per_page=8'),
      api.get('/categories'),
      api.get('/reviews'),
    ])
    featuredProducts.value = prodRes.data.data || []
    categories.value = catRes.data
    reviews.value = revRes.data
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <section class="hero-section fade-in-up">
    <h1 class="fw-bold">Discover What's Next</h1>
    <p class="lead mb-4">
      Handpicked products, fast delivery, and an experience built for you.
    </p>
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
    <div v-for="cat in categories" :key="cat.id" class="col-6 col-md-3 mb-3">
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
  <LoadingSpinner v-if="loading" />
  <div v-else class="row">
    <div v-for="product in featuredProducts" :key="product.id" class="col-6 col-md-3 mb-4">
      <ProductCard :product="product" />
    </div>
  </div>

  <div class="features-wrapper position-relative" style="margin-top: 56px; margin-bottom: 56px; padding: 48px 0 40px;">
    <!-- Decorative background blobs -->
    <div class="position-absolute" style="top: -40px; right: -60px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(99,102,241,0.10), transparent 70%); border-radius: 50%; pointer-events: none; z-index: 0;"></div>
    <div class="position-absolute" style="bottom: -30px; left: -80px; width: 240px; height: 240px; background: radial-gradient(circle, rgba(168,85,247,0.08), transparent 70%); border-radius: 50%; pointer-events: none; z-index: 0;"></div>

    <div class="section-header fade-in-up position-relative" style="z-index: 1;">
      <span class="header-icon"><i class="bi bi-info-circle"></i></span>
      <h2>Why Shop With Us</h2>
      <span class="header-line"></span>
    </div>

    <p class="text-center text-muted mb-5 fade-in-up position-relative" style="z-index: 1; max-width: 580px; margin-left: auto; margin-right: auto; line-height: 1.7;">
      GlobalStore is your trusted destination for quality products at unbeatable prices.
      We curate every item with care, ensuring you get the best value with every purchase.
    </p>

    <div class="row g-4 position-relative" style="z-index: 1;">
      <div class="col-md-6 col-lg-3" v-for="(feature, i) in features" :key="i"
        :style="`animation: fadeInUp 0.5s ease ${i * 0.12}s both;`">
        <div class="feature-card h-100">
          <div class="card-body text-center p-4">
            <div class="feature-icon-wrap mb-3" :style="`background: ${feature.iconBg}; color: ${feature.iconColor}; box-shadow: 0 8px 24px ${feature.shadow};`">
              <i :class="feature.icon" class="feature-icon"></i>
            </div>
            <h6 class="fw-bold mb-2" style="font-size: 16px;">{{ feature.title }}</h6>
            <p class="text-muted small mb-0" style="line-height: 1.6;">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section-header fade-in-up" style="margin-top: 48px">
    <span class="header-icon"><i class="bi bi-chat-dots"></i></span>
    <h2>Latest Reviews</h2>
    <span class="header-line"></span>
  </div>
  <div v-if="reviews.length === 0" class="text-center py-4 text-muted">
    <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
    <p>No reviews yet.</p>
  </div>
  <div v-else class="row mb-5">
    <div v-for="review in reviews" :key="review.id" class="col-md-6 mb-3">
      <div class="card review-card p-3 h-100 border-0 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <div
              class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
              style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--color-gradient-start), var(--color-gradient-end)); font-size: 14px;"
            >
              {{ review.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <div>
              <strong>{{ review.user?.name || 'User' }}</strong>
              <div class="text-muted small" style="font-size: 12px;">
                on <router-link :to="`/products/${review.product?.id}`" class="fw-semibold">{{ review.product?.name || 'Product' }}</router-link>
              </div>
            </div>
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

  <StoreLocation class="mt-5" />
</template>
