import axios from 'axios'
import router from '../router'
import { cachedRequest, clearCache } from '../composables/useApiCache'

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Accept': 'application/json',
  },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (res) => res,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      clearCache()
      router.push('/')
    }
    return Promise.reject(error)
  }
)

const originalGet = api.get
api.get = function (url, config = {}) {
  if (config.cache === false) {
    return originalGet(url, config)
  }
  return cachedRequest(api, { ...config, method: 'get', url })
}

api.clearCache = clearCache

export default api
