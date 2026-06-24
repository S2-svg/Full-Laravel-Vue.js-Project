<script setup>
defineProps({
  mapEmbed: {
    type: String,
    default: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3909.023!2d104.88!3d11.55!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTHCsDM2JzE0LjQiTiAxMDjCsDU2JzEyLjQiRQ!5e0!3m2!1sen!2skh!4v1700000000000',
  },
  name: { type: String, default: 'GlobalStore' },
  city: { type: String, default: 'PHNOM PENH' },
  country: { type: String, default: 'CAMBODIA' },
  address: { type: String, default: 'BP 511 St. 371 Phum Tropeang Chhuk (Borey Sorla) Sangkat Tek Thla, Khan Sen Sok' },
  phone: { type: String, default: '+885 11 244 930' },
  hours: { type: String, default: '24/7' },
  rating: { type: Number, default: 4.9 },
  reviewCount: { type: Number, default: 162 },
})
</script>

<template>
  <div class="store-location-section">
    <div class="section-header">
      <span class="header-icon"><i class="bi bi-geo-alt"></i></span>
      <h2>Our Location</h2>
      <span class="header-line"></span>
    </div>

    <div class="card border-0 shadow-sm store-location-card">
      <div class="card-body p-0">
        <div class="row g-0">
          <div class="col-lg-5 p-4 d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="fw-bold text-uppercase store-city">{{ city }} | {{ country }}</span>
              </div>
              <p class="text-muted small mb-2 store-address">
                <i class="bi bi-geo-alt me-1"></i>{{ address }}
              </p>
              <p class="text-muted small mb-2 store-phone">
                <i class="bi bi-telephone me-1"></i>{{ phone }}
              </p>
              <p class="text-muted small mb-3 store-hours">
                <i class="bi bi-clock me-1"></i>{{ hours }}
              </p>
              <div class="d-flex align-items-center gap-2 mb-3 store-rating">
                <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                <span class="fw-bold">{{ rating }}</span>
                <span class="text-muted small">({{ reviewCount }})</span>
              </div>
            </div>
            <a href="https://maps.google.com" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm mt-3 align-self-start">
              <i class="bi bi-map me-1"></i>Open in Google Maps
            </a>
          </div>
          <div class="col-lg-7 store-map-wrapper">
            <iframe
              :src="mapEmbed"
              width="100%"
              height="100%"
              style="border: 0;"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              title="Store Location"
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.store-location-card {
  border-radius: var(--radius-md);
  overflow: hidden;
}
.store-map-wrapper {
  min-height: 280px;
  height: 340px;
}
.store-city {
  color: var(--color-primary);
  letter-spacing: 0.5px;
  font-size: 14px;
}
@media (max-width: 991px) {
  .store-map-wrapper {
    height: 260px;
  }
}
</style>
