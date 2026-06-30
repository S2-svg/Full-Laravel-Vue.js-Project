import { computed } from 'vue'

export function getPasswordStrength(pw) {
  if (!pw) return 0
  let score = 0
  if (pw.length >= 8) score++
  if (pw.length >= 12) score++
  if (/[A-Z]/.test(pw)) score++
  if (/[0-9]/.test(pw)) score++
  if (/[^A-Za-z0-9]/.test(pw)) score++
  return Math.min(score, 4)
}

const strengthColors = ['', '#f43f5e', '#f59e0b', '#10b981', '#10b981']
const strengthLabels = ['', 'Weak', 'Fair', 'Good', 'Strong']

export function getStrengthClass(bar, pw) {
  const strength = getPasswordStrength(pw)
  if (!pw) return 'strength-empty'
  return bar <= strength ? `strength-active-${strength}` : 'strength-inactive'
}

export function getStrengthColor(pw) {
  const strength = getPasswordStrength(pw)
  return strengthColors[strength] || '#94a3b8'
}

export function getStrengthLabel(pw) {
  const strength = getPasswordStrength(pw)
  return strengthLabels[strength] || ''
}

export function usePasswordStrength(passwordRef) {
  const strength = computed(() => getPasswordStrength(passwordRef.value))
  const strengthColor = computed(() => strengthColors[strength.value] || '#94a3b8')
  const strengthLabel = computed(() => strengthLabels[strength.value] || '')

  function getClass(bar) {
    if (!passwordRef.value) return 'strength-empty'
    return bar <= strength.value ? `strength-active-${strength.value}` : 'strength-inactive'
  }

  return { strength, strengthColor, strengthLabel, getStrengthClass: getClass }
}
