<template>
  <div class="vacancy-card card mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div class="flex-grow-1">
          <div class="d-flex align-items-center mb-2">
            <div class="icon-bg-primary me-3">
              <BriefcaseIcon style="width: 1.25rem; height: 1.25rem;" />
            </div>
            <div>
              <h5 class="card-title mb-1">{{ vacancy.title }}</h5>
              <p class="text-muted small mb-0">
                {{ formatSalary(vacancy.salary) }} • {{ formatDate(vacancy.created_at) }}
              </p>
            </div>
          </div>
          <p v-if="showDescription" class="mb-2 text-muted lh-sm" style="max-height: 3rem; overflow: hidden;">
            {{ vacancy.description }}
          </p>
        </div>
        <div class="d-flex gap-2 ms-3">
          <slot name="actions">
            <NuxtLink
              :to="`/vacancies/${vacancy.id}`"
              class="btn btn-outline-primary btn-sm"
            >
              {{ buttonText }}
            </NuxtLink>
          </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { BriefcaseIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  vacancy: {
    type: Object,
    required: true
  },
  buttonText: {
    type: String,
    default: 'Подробнее'
  },
  showDescription: {
    type: Boolean,
    default: false
  }
})

const { formatSalary, formatDate } = useVacancies()
</script>