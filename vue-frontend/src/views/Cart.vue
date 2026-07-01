<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import EmptyState from '../components/EmptyState.vue'
import { useToast } from '../composables/useToast'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const toast = useToast()
const cartStore = useCartStore()

const items = ref([])
const ready = ref(false)

/* ─── Remove confirmation modal ─── */
const showRemoveModal = ref(false)
const itemToRemove = ref(null)
const removing = ref(false)

function confirmRemove(item) {
  itemToRemove.value = item
  showRemoveModal.value = true
}

function cancelRemove() {
  showRemoveModal.value = false
  itemToRemove.value = null
}

async function executeRemove() {
  if (!itemToRemove.value) return
  removing.value = true
  try {
    await api.delete(`/carts/${itemToRemove.value.id}`)
    items.value = items.value.filter(i => i.id !== itemToRemove.value.id)
    cartStore.fetchCount()
    toast.success('Item removed from cart')
    showRemoveModal.value = false
    itemToRemove.value = null
  } catch (e) {
    toast.error('Error removing item')
  } finally {
    removing.value = false
  }
}

/* ─── Computed values ─── */
const subtotal = computed(() =>
  items.value.reduce((sum, item) => sum + ((item.product?.final_price ?? item.product?.price) || 0) * item.quantity, 0)
)

const totalSavings = computed(() =>
  items.value.reduce((sum, item) => {
    if (item.product?.has_discount && item.product?.price && item.product?.final_price) {
      return sum + ((item.product.price - item.product.final_price) * item.quantity)
    }
    return sum
  }, 0)
)

const totalItemsCount = computed(() =>
  items.value.reduce((sum, item) => sum + item.quantity, 0)
)

const hasStockIssues = computed(() => items.value.some(item => item.product && item.product.stock <= 0))
const stockIssueItems = computed(() => items.value.filter(item => item.product && item.product.stock <= 0))
const lowStockItems = computed(() => items.value.filter(item => item.product && item.product.stock > 0 && item.product.stock <= 5))

/* ─── Lifecycle ─── */
onMounted(async () => {
  try {
    const res = await api.get('/carts')
    items.value = res.data
  } finally {
    ready.value = true
  }
})

/* ─── Quantity ─── */
async function updateQty(item, qty) {
  if (qty < 1 || qty > (item.product?.stock || 999)) return
  try {
    await api.put(`/carts/${item.id}`, { quantity: qty })
    item.quantity = qty
    cartStore.fetchCount()
  } catch (e) {
    toast.error(e.response?.data?.message || 'Error updating quantity')
  }
}

function isMinQty(item) {
  return item.quantity <= 1
}

function isMaxQty(item) {
  return item.quantity >= (item.product?.stock || 999)
}

/* ─── Checkout ─── */
function checkout() {
  if (hasStockIssues.value) return
  router.push('/checkout')
}

function continueShopping() {
  router.push('/products')
}
</script>

<template>
  <div class="fade-in-up">
    <div class="section-header">
      <span class="header-icon"><i class="bi bi-cart3"></i></span>
      <h2>Shopping Cart</h2>
      <span class="header-line"></span>
    </div>

    <!-- Empty state -->
    <EmptyState
      v-if="ready && items.length === 0"
      icon="bi-cart-x"
      title="Your cart is empty"
      message="Looks like you haven't added anything yet."
      linkTo="/products"
      linkText="Start Shopping"
    />

    <!-- Loading -->
    <div v-else-if="!ready" class="text-center py-5">
      <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="text-muted">Loading your cart...</p>
    </div>

    <!-- Cart content -->
    <div v-else class="row g-4">
      <!-- Left: cart items -->
      <div class="col-lg-8">
        <!-- Stock alerts -->
        <div v-if="stockIssueItems.length > 0" class="alert alert-danger d-flex align-items-center gap-2 mb-3 stock-alert">
          <i class="bi bi-exclamation-triangle-fill fs-5"></i>
          <span>
            <strong>Out of stock:</strong>
            <span v-for="(item, i) in stockIssueItems" :key="item.id">
              {{ item.product?.name }}<span v-if="i < stockIssueItems.length - 1">, </span>
            </span>
            — please remove these items to proceed.
          </span>
        </div>

        <div v-if="lowStockItems.length > 0" class="alert alert-warning d-flex align-items-center gap-2 mb-3 stock-alert">
          <i class="bi bi-exclamation-circle-fill fs-5"></i>
          <span>
            <strong>Low stock:</strong>
            <span v-for="(item, i) in lowStockItems" :key="item.id">
              {{ item.product?.name }} ({{ item.product?.stock }} left)<span v-if="i < lowStockItems.length - 1">, </span>
            </span>
            — quantities may be limited at checkout.
          </span>
        </div>

        <!-- Cart items -->
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body p-0">
            <div
              v-for="(item, idx) in items"
              :key="item.id"
              class="cart-item-row p-4"
              :class="{ 'border-bottom': idx < items.length - 1 }"
            >
              <div class="row align-items-center g-3">
                <!-- Image -->
                <div class="col-md-2 col-4">
                  <img
                    v-if="item.product?.image"
                    :src="`/storage/${item.product.image}`"
                    class="cart-item-image"
                    :alt="item.product.name"
                    loading="lazy"
                  />
                  <div v-else class="cart-item-image-placeholder">
                    <i class="bi bi-image"></i>
                  </div>
                </div>

                <!-- Name & price -->
                <div class="col-md-3 col-8">
                  <router-link
                    :to="`/products/${item.product_id}`"
                    class="cart-item-name"
                  >
                    {{ item.product?.name }}
                  </router-link>
                  <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="item-price">${{ item.product?.final_price ?? item.product?.price }}</span>
                    <span v-if="item.product?.has_discount" class="text-muted text-decoration-line-through small">${{ item.product?.price }}</span>
                    <span v-if="item.product?.has_discount" class="badge rounded-pill cart-discount-badge">-{{ item.product?.discount_percent }}%</span>
                  </div>
                  <span
                    v-if="item.product && item.product.stock <= 0"
                    class="badge rounded-pill mt-1 stock-badge-out"
                  >
                    <i class="bi bi-x-circle me-1"></i>Out of Stock
                  </span>
                  <span
                    v-else-if="item.product && item.product.stock <= 5"
                    class="badge rounded-pill mt-1 stock-badge-low"
                  >
                    <i class="bi bi-exclamation-circle me-1"></i>Only {{ item.product.stock }} left
                  </span>
                </div>

                <!-- Quantity controls -->
                <div class="col-md-3 col-6">
                  <div class="d-flex align-items-center gap-2">
                    <button
                      class="qty-btn"
                      :class="{ 'qty-btn-disabled': isMinQty(item) || item.product?.stock <= 0 }"
                      :disabled="isMinQty(item) || item.product?.stock <= 0"
                      @click="updateQty(item, item.quantity - 1)"
                      title="Decrease quantity"
                    >
                      <i class="bi bi-dash"></i>
                    </button>
                    <span
                      class="qty-display"
                      :class="{
                        'text-danger': item.product?.stock <= 0,
                        'qty-max': isMaxQty(item) && item.product?.stock > 0
                      }"
                    >
                      {{ item.quantity }}
                    </span>
                    <button
                      class="qty-btn"
                      :class="{ 'qty-btn-disabled': isMaxQty(item) || item.product?.stock <= 0 }"
                      :disabled="isMaxQty(item) || item.product?.stock <= 0"
                      @click="updateQty(item, item.quantity + 1)"
                      title="Increase quantity"
                    >
                      <i class="bi bi-plus"></i>
                    </button>
                    <span v-if="isMaxQty(item) && item.product?.stock > 0" class="small text-muted qty-max-label">
                      Max
                    </span>
                  </div>
                </div>

                <!-- Line total -->
                <div class="col-md-2 col-4">
                  <div class="line-total">${{ (((item.product?.final_price ?? item.product?.price) || 0) * item.quantity).toFixed(2) }}</div>
                </div>

                <!-- Remove -->
                <div class="col-md-2 col-2 text-end">
                  <button
                    class="btn btn-sm remove-btn"
                    @click="confirmRemove(item)"
                    title="Remove item"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Continue shopping -->
        <div class="d-flex align-items-center justify-content-between">
          <button class="btn btn-outline-secondary btn-sm" @click="continueShopping">
            <i class="bi bi-arrow-left me-1"></i>Continue Shopping
          </button>
        </div>
      </div>

      <!-- Right: Summary sidebar -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm summary-card">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-4 summary-title">
              <i class="bi bi-receipt me-2"></i>Order Summary
            </h5>

            <!-- Subtotal -->
            <div class="summary-row">
              <span class="summary-label">Subtotal ({{ totalItemsCount }} item{{ totalItemsCount !== 1 ? 's' : '' }})</span>
              <span class="summary-value">${{ subtotal.toFixed(2) }}</span>
            </div>

            <!-- Discount savings -->
            <div v-if="totalSavings > 0" class="summary-row summary-savings">
              <span class="summary-label">
                <i class="bi bi-tag-fill me-1"></i>Discount savings
              </span>
              <span class="summary-value text-success">−${{ totalSavings.toFixed(2) }}</span>
            </div>

            <!-- Shipping note -->
            <div class="summary-row summary-shipping">
              <span class="summary-label">Shipping</span>
              <span class="summary-value text-muted small">Calculated at checkout</span>
            </div>

            <hr class="summary-divider" />

            <!-- Total -->
            <div class="summary-row summary-total-row">
              <span class="summary-total-label">Estimated Total</span>
              <span class="summary-total-value">${{ subtotal.toFixed(2) }}</span>
            </div>

            <!-- Checkout button -->
            <button
              class="btn btn-primary btn-lg w-100 mt-3 checkout-btn"
              :class="{ 'btn-disabled': hasStockIssues }"
              :disabled="hasStockIssues"
              @click="checkout"
            >
              <i class="bi bi-credit-card me-2"></i>
              {{ hasStockIssues ? 'Remove out of stock items' : 'Proceed to Checkout' }}
            </button>

            <!-- Secure checkout note -->
            <div class="text-center mt-3">
              <small class="text-muted">
                <i class="bi bi-shield-check me-1"></i>
                Secure checkout
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ─── Remove Confirmation Modal ─── -->
    <Teleport to="body">
      <transition name="modal-fade">
        <div v-if="showRemoveModal" class="modal-backdrop-custom" @click.self="cancelRemove" @keydown.escape="cancelRemove" tabindex="-1">
          <div class="modal-dialog-custom">
            <div class="modal-card">
              <div class="modal-icon-wrap">
                <i class="bi bi-exclamation-triangle"></i>
              </div>
              <h5 class="fw-bold mb-2">Remove Item</h5>
              <p class="text-muted mb-4">
                Are you sure you want to remove
                <strong>{{ itemToRemove?.product?.name || 'this item' }}</strong>
                from your cart?
              </p>
              <div class="d-flex gap-3 justify-content-center">
                <button class="btn btn-outline-secondary px-4" @click="cancelRemove" :disabled="removing">
                  Cancel
                </button>
                <button class="btn btn-danger px-4" @click="executeRemove" :disabled="removing">
                  <i v-if="removing" class="bi bi-hourglass-split me-1"></i>
                  <i v-else class="bi bi-trash me-1"></i>
                  {{ removing ? 'Removing...' : 'Remove' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </Teleport>
  </div>
</template>

<style scoped>
/* ─── Cart Item Row ─── */
.cart-item-row {
  transition: background var(--transition-fast);
}

.cart-item-row:hover {
  background: rgba(99, 102, 241, 0.02);
}

.cart-item-name {
  font-weight: 600;
  font-size: 15px;
  color: var(--color-text);
  text-decoration: none;
  transition: color var(--transition-fast);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

@media (max-width: 575.98px) {
  .cart-item-name {
    font-size: 13px;
  }
  .cart-item-row {
    padding: 1rem !important;
  }
  .qty-btn {
    width: 30px;
    height: 30px;
  }
  .qty-display {
    width: 36px;
    font-size: 14px;
  }
  .line-total {
    font-size: 14px;
  }
  .item-price {
    font-size: 12px;
  }
  .cart-item-image {
    height: 60px;
  }
  .cart-item-image-placeholder {
    height: 60px;
  }
}

.cart-item-name:hover {
  color: var(--color-primary);
}

.item-price {
  font-weight: 600;
  font-size: 14px;
  color: var(--color-primary);
}

.line-total {
  font-weight: 700;
  font-size: 16px;
  color: var(--color-text);
}

/* ─── Quantity ─── */
.qty-btn {
  width: 34px;
  height: 34px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  border: 2px solid var(--color-border);
  background: var(--color-surface);
  color: var(--color-text);
  font-weight: 600;
  transition: all var(--transition-fast);
  cursor: pointer;
}

.qty-btn:hover:not(:disabled) {
  border-color: var(--color-primary);
  color: var(--color-primary);
  background: rgba(99, 102, 241, 0.04);
}

.qty-btn:disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

.qty-display {
  width: 44px;
  text-align: center;
  font-weight: 700;
  font-size: 16px;
  color: var(--color-text);
}

.qty-max {
  color: var(--color-primary);
}

.qty-max-label {
  font-weight: 600;
  font-size: 11px;
  color: var(--color-primary);
  background: rgba(99, 102, 241, 0.08);
  padding: 2px 8px;
  border-radius: 4px;
}

/* ─── Remove Button ─── */
.remove-btn {
  width: 36px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  border: 2px solid transparent;
  color: var(--color-text-secondary);
  transition: all var(--transition-fast);
  padding: 0;
}

.remove-btn:hover {
  border-color: var(--color-danger);
  color: var(--color-danger);
  background: rgba(244, 63, 94, 0.06);
}

/* ─── Stock Alerts ─── */
.stock-alert {
  border: none;
  border-radius: 12px;
  font-size: 14px;
  word-break: break-word;
}

.cart-discount-badge {
  background: #fee2e2;
  color: #dc2626;
  font-size: 10px;
}

.stock-badge-out {
  background: #fee2e2;
  color: #991b1b;
  font-size: 11px;
}

.stock-badge-low {
  background: #fef3c7;
  color: #92400e;
  font-size: 11px;
}

/* ─── Summary Card ─── */
.summary-card {
  position: sticky;
  top: 100px;
}

@media (max-width: 991.98px) {
  .summary-card {
    position: static;
  }
}

.summary-title {
  font-size: 17px;
  display: flex;
  align-items: center;
  color: var(--color-text);
}

.summary-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 0;
}

.summary-label {
  font-size: 14px;
  color: var(--color-text-secondary);
}

.summary-value {
  font-weight: 600;
  font-size: 14px;
  color: var(--color-text);
}

.summary-savings {
  padding: 8px 0;
}

.summary-savings .summary-label {
  display: flex;
  align-items: center;
  gap: 2px;
  font-size: 13px;
}

.summary-savings .summary-value {
  font-size: 13px;
}

.summary-shipping .summary-label,
.summary-shipping .summary-value {
  font-size: 13px;
}

.summary-divider {
  margin: 12px 0;
  opacity: 0.5;
}

.summary-total-row {
  padding: 4px 0;
}

.summary-total-label {
  font-size: 17px;
  font-weight: 700;
  color: var(--color-text);
}

.summary-total-value {
  font-size: 22px;
  font-weight: 800;
  color: var(--color-primary);
}

.checkout-btn {
  border-radius: 12px;
  padding: 14px;
  font-size: 15px;
  font-weight: 700;
}

/* ─── Remove Modal ─── */
.modal-backdrop-custom {
  position: fixed;
  inset: 0;
  z-index: 9999;
  background: rgba(15, 23, 42, 0.5);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.modal-dialog-custom {
  width: 100%;
  max-width: 420px;
  animation: modalSlideUp 0.25s ease;
}

.modal-card {
  background: var(--color-surface);
  border-radius: var(--radius-md);
  padding: 2rem;
  text-align: center;
  box-shadow: 0 25px 50px rgba(15, 23, 42, 0.2);
  border: 1px solid var(--color-border);
}

.modal-icon-wrap {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  background: rgba(244, 63, 94, 0.1);
  color: var(--color-danger);
  font-size: 24px;
}

@keyframes modalSlideUp {
  from {
    opacity: 0;
    transform: translateY(20px) scale(0.96);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* ─── Modal Transitions ─── */
.modal-fade-enter-active {
  transition: opacity 0.2s ease;
}

.modal-fade-leave-active {
  transition: opacity 0.15s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
