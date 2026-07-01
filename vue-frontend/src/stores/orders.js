import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../api'

export const useOrderStore = defineStore('orders', () => {
  const count = ref(0)

  async function fetchCount() {
    if (!localStorage.getItem('token')) {
      count.value = 0
      return
    }
    try {
      const res = await api.get('/orders')
      count.value = res.data.length
    } catch {
      count.value = 0
    }
  }

  function increment(amount = 1) {
    count.value += amount
  }

  function reset() {
    count.value = 0
  }

  return { count, fetchCount, increment, reset }
})
