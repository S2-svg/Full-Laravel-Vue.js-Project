const cache = new Map()
const pending = new Map()

const DEFAULTS = {
  categories: { ttl: 300000 },
  products: { ttl: 60000 },
  orders: { ttl: 30000 },
  carts: { ttl: 10000 },
  reviews: { ttl: 60000 },
}

function getKey(config) {
  const params = config.params ? JSON.stringify(config.params) : ''
  return `${config.url}:${params}`
}

function getConfig(url) {
  for (const [prefix, cfg] of Object.entries(DEFAULTS)) {
    if (url.includes(prefix)) return cfg
  }
  return { ttl: 15000 }
}

export async function cachedRequest(api, config) {
  const key = getKey(config)

  const entry = cache.get(key)
  const now = Date.now()

  if (entry && now - entry.timestamp < entry.ttl) {
    return entry.data
  }

  if (pending.has(key)) {
    return pending.get(key)
  }

  const promise = api(config).then(res => {
    const { ttl } = getConfig(config.url)
    cache.set(key, { data: res, timestamp: Date.now(), ttl })
    pending.delete(key)
    return res
  }).catch(err => {
    pending.delete(key)
    if (entry) return entry.data
    throw err
  })

  pending.set(key, promise)

  if (entry) {
    return entry.data
  }

  return promise
}

export function clearCache(pattern) {
  if (!pattern) {
    cache.clear()
    return
  }
  for (const key of cache.keys()) {
    if (key.includes(pattern)) cache.delete(key)
  }
}
