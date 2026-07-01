import { defineStore } from 'pinia'
import { ref } from 'vue'

let nextId = 0

export const useToastStore = defineStore('toast', () => {
  const toasts = ref([])
  const timers = new Map()

  function add(message, type = 'info', duration = 4000) {
    const id = ++nextId
    toasts.value.push({ id, message, type })
    const timer = setTimeout(() => remove(id), duration)
    timers.set(id, timer)
  }

  function remove(id) {
    const timer = timers.get(id)
    if (timer) {
      clearTimeout(timer)
      timers.delete(id)
    }
    toasts.value = toasts.value.filter(t => t.id !== id)
  }

  function success(message) { add(message, 'success') }
  function error(message) { add(message, 'danger') }
  function warning(message) { add(message, 'warning') }
  function info(message) { add(message, 'info') }

  return { toasts, add, remove, success, error, warning, info }
})
