<script setup>
import { useToastStore } from '../stores/toast'

const toast = useToastStore()

const bgClass = (type) => `text-bg-${type}`
</script>

<template>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div
      v-for="t in toast.toasts"
      :key="t.id"
      class="toast show align-items-center border-0"
      :class="bgClass(t.type)"
      role="alert"
    >
      <div class="d-flex">
        <div class="toast-body">
          <i
            :class="{
              'bi-check-circle-fill': t.type === 'success',
              'bi-exclamation-triangle-fill': t.type === 'danger',
              'bi-exclamation-circle-fill': t.type === 'warning',
              'bi-info-circle-fill': t.type === 'info',
            }"
            class="me-2"
          ></i>
          {{ t.message }}
        </div>
        <button
          type="button"
          class="btn-close btn-close-white me-2 m-auto"
          @click="toast.remove(t.id)"
        ></button>
      </div>
    </div>
  </div>
</template>
