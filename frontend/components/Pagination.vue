<template>
  <div v-if="totalPages > 1" class="card-footer">
    <div class="d-flex justify-content-between align-items-center">
      <small class="text-muted">
        Страница {{ currentPage }} из {{ totalPages }} ({{ totalItems }} {{ itemsText }})
      </small>
      <nav aria-label="Навигация по страницам">
        <ul class="pagination pagination-sm mb-0">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <button
              class="page-link"
              @click="goToPage(currentPage - 1)"
              :disabled="currentPage === 1"
            >
              Предыдущая
            </button>
          </li>

          <li
            v-for="page in visiblePages"
            :key="page"
            class="page-item"
            :class="{ active: page === currentPage }"
          >
            <button v-if="page !== '...'" class="page-link" @click="goToPage(page)">
              {{ page }}
            </button>
            <span v-else class="page-link">{{ page }}</span>
          </li>

          <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <button
              class="page-link"
              @click="goToPage(currentPage + 1)"
              :disabled="currentPage === totalPages"
            >
              Следующая
            </button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  totalItems: {
    type: Number,
    required: true
  },
  itemsPerPage: {
    type: Number,
    default: 10
  },
  itemsText: {
    type: String,
    default: 'записей'
  }
})

const emit = defineEmits(['update:currentPage'])

const totalPages = computed(() => {
  return Math.ceil(props.totalItems / props.itemsPerPage)
})

const visiblePages = computed(() => {
  if (totalPages.value <= 1) return []

  const pages = []
  const delta = 2

  pages.push(1)

  if (props.currentPage > delta + 2) {
    pages.push('...')
  }

  const start = Math.max(2, props.currentPage - delta)
  const end = Math.min(totalPages.value - 1, props.currentPage + delta)

  for (let i = start; i <= end; i++) {
    pages.push(i)
  }

  if (props.currentPage < totalPages.value - delta - 1) {
    pages.push('...')
  }

  if (totalPages.value > 1 && !pages.includes(totalPages.value)) {
    pages.push(totalPages.value)
  }

  return pages
})

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value && page !== props.currentPage) {
    emit('update:currentPage', page)
  }
}
</script>

<style scoped>
.pagination .page-link {
  border-color: #dee2e6;
  color: #6c757d;
}

.pagination .page-item.active .page-link {
  background-color: #0d6efd;
  border-color: #0d6efd;
  color: white;
}

.pagination .page-link:hover {
  color: #0d6efd;
  background-color: #f8f9fa;
  border-color: #dee2e6;
}

.pagination .page-item.disabled .page-link {
  color: #6c757d;
  background-color: white;
  border-color: #dee2e6;
}
</style>