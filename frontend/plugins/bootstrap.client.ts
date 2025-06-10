import * as bootstrap from 'bootstrap'

export default defineNuxtPlugin(() => {
  if (process.client) {
    window.bootstrap = bootstrap
  }
})