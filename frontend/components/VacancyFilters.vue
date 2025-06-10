<template>
  <div class="card mb-4">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <label for="searchTitle" class="form-label">Поиск по названию</label>
          <input
            id="searchTitle"
            v-model="localFilters.title"
            type="text"
            class="form-control"
            placeholder="Введите название вакансии..."
            @input="debouncedEmit"
          />
        </div>

        <div class="col-md-2">
          <label for="salaryFrom" class="form-label">Зарплата от</label>
          <input
            id="salaryFrom"
            v-model.number="localFilters.salary_from"
            type="number"
            class="form-control"
            :class="{ 'is-invalid': salaryValidationError }"
            placeholder="0"
            min="0"
            @input="debouncedEmit"
          />
        </div>

        <div class="col-md-2">
          <label for="salaryTo" class="form-label">Зарплата до</label>
          <input
            id="salaryTo"
            v-model.number="localFilters.salary_to"
            type="number"
            class="form-control"
            :class="{ 'is-invalid': salaryValidationError }"
            placeholder="1000000"
            min="0"
            @input="debouncedEmit"
          />
          <div v-if="salaryValidationError" class="invalid-feedback">
            {{ salaryValidationError }}
          </div>
        </div>

        <div class="col-md-3">
          <label for="sortSelect" class="form-label">Сортировка</label>
          <select
            id="sortSelect"
            v-model="localFilters.sort"
            class="form-select filter-height"
            @change="debouncedEmit"
          >
            <option value="created_at:desc">Дата создания (новые сначала)</option>
            <option value="created_at:asc">Дата создания (старые сначала)</option>
            <option value="salary:desc">Зарплата (по убыванию)</option>
            <option value="salary:asc">Зарплата (по возрастанию)</option>
            <option value="title:asc">Название (А-Я)</option>
            <option value="title:desc">Название (Я-А)</option>
          </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
          <button
            type="button"
            class="btn btn-outline-secondary w-100 filter-height"
            @click="resetFilters"
          >
            <XMarkIcon class="filter-icon me-2" />
            Сбросить
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useDebounceFn } from '@vueuse/core'
import { XMarkIcon } from '@heroicons/vue/24/outline'

const DEBOUNCE_DELAY = 500
const DEFAULT_SORT = 'created_at:desc'

const props = defineProps({
  filters: {
    type: Object,
    default: () => ({
      title: '',
      salary_from: '',
      salary_to: '',
      sort: DEFAULT_SORT
    })
  }
})

const emit = defineEmits(['change'])

const localFilters = reactive({
  title: props.filters.title || '',
  salary_from: props.filters.salary_from || '',
  salary_to: props.filters.salary_to || '',
  sort: props.filters.sort || DEFAULT_SORT
})

const salaryValidationError = computed(() => {
  const from = Number(localFilters.salary_from)
  const to = Number(localFilters.salary_to)

  if (from && to && from > to) {
    return 'Зарплата "от" не может быть больше зарплаты "до"'
  }

  if (from < 0 || to < 0) {
    return 'Зарплата не может быть отрицательной'
  }

  return null
})

const createFilterObject = () => {
  const [sortBy, sortOrder] = localFilters.sort.split(':')

  return {
    title: localFilters.title,
    salary_from: localFilters.salary_from,
    salary_to: localFilters.salary_to,
    sort_by: sortBy,
    sort_order: sortOrder
  }
}

const debouncedEmit = useDebounceFn(() => {
  if (!salaryValidationError.value) {
    emit('change', createFilterObject())
  }
}, DEBOUNCE_DELAY)

const resetFilters = () => {
  localFilters.title = ''
  localFilters.salary_from = ''
  localFilters.salary_to = ''
  localFilters.sort = DEFAULT_SORT

  emit('change', {
    title: '',
    salary_from: '',
    salary_to: '',
    sort_by: 'created_at',
    sort_order: 'desc'
  })
}

watch(() => props.filters, (newFilters) => {
  if (newFilters) {
    localFilters.title = newFilters.title || ''
    localFilters.salary_from = newFilters.salary_from || ''
    localFilters.salary_to = newFilters.salary_to || ''
    localFilters.sort = `${newFilters.sort_by || 'created_at'}:${newFilters.sort_order || 'desc'}`
  }
}, { immediate: true })
</script>

<style scoped>
.filter-height {
  height: 50px;
}

.filter-icon {
  width: 1rem;
  height: 1rem;
}

.form-select {
  background-position: right 0.75rem center;
}

.form-select:focus {
  border-color: #86b7fe;
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.is-invalid {
  border-color: #dc3545;
}

.is-invalid:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>