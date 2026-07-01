import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../api'

export const useCategoriesStore = defineStore('categories', () => {
  const items = ref([])
  const loaded = ref(false)

  async function fetch() {
    if (loaded.value) return items.value
    const res = await api.get('/categories')
    items.value = res.data
    loaded.value = true
    return items.value
  }

  function set(data) {
    items.value = data
    loaded.value = true
  }

  function reset() {
    items.value = []
    loaded.value = false
  }

  return { items, loaded, fetch, set, reset }
})
