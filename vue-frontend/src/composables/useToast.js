import { useToastStore } from '../stores/toast'

export function useToast() {
  const toast = useToastStore()

  function notifySuccess(msg) { toast.success(msg) }
  function notifyError(msg) { toast.error(msg || 'Something went wrong') }
  function notifyWarning(msg) { toast.warning(msg) }
  function notifyInfo(msg) { toast.info(msg) }

  return {
    success: notifySuccess,
    error: notifyError,
    warning: notifyWarning,
    info: notifyInfo,
  }
}
