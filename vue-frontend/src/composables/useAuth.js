import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'

export function useAuth() {
  const auth = useAuthStore()
  const router = useRouter()
  const { isLoggedIn, isAdmin } = storeToRefs(auth)

  async function requireAuth() {
    if (!isLoggedIn.value) {
      router.push('/login')
      return false
    }
    return true
  }

  async function handleLogin(credentials) {
    await auth.login(credentials)
  }

  async function handleRegister(data) {
    await auth.register(data)
  }

  function handleLogout() {
    auth.logout()
    router.push('/login')
  }

  return { auth, isLoggedIn, isAdmin, requireAuth, handleLogin, handleRegister, handleLogout }
}
