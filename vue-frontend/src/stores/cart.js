import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../api'

export const useCartStore = defineStore('cart', () => {
  const count = ref(0)

  async function fetchCount() {
    if (!localStorage.getItem('token')) {
      count.value = 0
      return
    }
    try {
      const res = await api.get('/carts')
      count.value = res.data.reduce((sum, item) => sum + item.quantity, 0)
    } catch {
      count.value = 0
    }
  }

  function increment(amount = 1) {
    count.value += amount
  }

  function decrement(amount = 1) {
    count.value = Math.max(0, count.value - amount)
  }

  function reset() {
    count.value = 0
  }

  return { count, fetchCount, increment, decrement, reset }
})
