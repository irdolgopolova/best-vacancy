import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

export default defineNuxtPlugin(async (nuxtApp) => {
  const options = {
    position: 'top-right',
    timeout: 4000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: 'button',
    icon: true,
    rtl: false
  }

  nuxtApp.vueApp.use(Toast, options)

  // Получаем toast instance
  const { useToast } = await import('vue-toastification')
  const toast = useToast()

  // Глобальная доступность toast
  if (process.client) {
    window.$toast = {
      success: (title, message) => {
        const text = message ? `${title}: ${message}` : title
        toast.success(text)
      },
      error: (title, message) => {
        const text = message ? `${title}: ${message}` : title
        toast.error(text)
      },
      info: (title, message) => {
        const text = message ? `${title}: ${message}` : title
        toast.info(text)
      },
      warning: (title, message) => {
        const text = message ? `${title}: ${message}` : title
        toast.warning(text)
      }
    }
  }

  return {
    provide: {
      toast
    }
  }
})
