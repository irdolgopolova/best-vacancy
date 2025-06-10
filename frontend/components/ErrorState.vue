<template>
  <div class="text-center py-5">
    <component
      :is="icon"
      class="error-icon text-danger mb-3"
    />
    <h6>{{ title }}</h6>
    <p class="text-muted mb-4">
      {{ message }}
    </p>
    <div class="d-flex gap-3 justify-content-center">
      <button
        v-if="showRetry"
        class="btn btn-primary"
        @click="$emit('retry')"
        :disabled="loading"
      >
        <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
        {{ loading ? 'Загрузка...' : 'Попробовать снова' }}
      </button>
      <NuxtLink
        v-if="backUrl"
        :to="backUrl"
        class="btn btn-outline-secondary"
      >
        {{ backText }}
      </NuxtLink>
    </div>
  </div>
</template>

<script setup>
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

defineProps({
  icon: {
    type: [Object, Function],
    default: ExclamationTriangleIcon
  },
  title: {
    type: String,
    default: 'Ошибка загрузки'
  },
  message: {
    type: String,
    default: 'Не удалось загрузить данные'
  },
  showRetry: {
    type: Boolean,
    default: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  backUrl: {
    type: String,
    default: null
  },
  backText: {
    type: String,
    default: 'Назад'
  }
})

defineEmits(['retry'])
</script>

<style scoped>
.error-icon {
  width: 3rem;
  height: 3rem;
}
</style>