<template>
  <div class="container-fluid">
    <HeroBanner title="Управление вакансиями" subtitle="Создавайте, редактируйте и управляйте вакансиями">
      <template #actions>
        <NuxtLink to="/vacancies/create" class="btn btn-primary">
          <PlusIcon style="width: 1.25rem; height: 1.25rem;" class="me-2" />
          Создать вакансию
        </NuxtLink>
      </template>
    </HeroBanner>

    <VacancyFilters
      :filters="currentFilters"
      @change="handleFiltersChange"
    />

    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Список вакансий</h5>
          <small v-if="!loading" class="text-muted">
            Найдено {{ totalItems }} {{ pluralizeVacancies(totalItems) }}
          </small>
        </div>
      </div>
      <div class="card-body p-0">
        <LoadingSpinner v-if="loading" />

        <EmptyState
          v-else-if="error"
          title="Ошибка загрузки"
          :description="error.message || 'Не удалось загрузить список вакансий'"
        />

        <EmptyState
          v-else-if="!vacancies || vacancies.length === 0"
          title="Вакансии не найдены"
          description="Попробуйте изменить параметры поиска или создайте новую вакансию"
        />

        <div v-else class="p-3">
          <VacancyCard
            v-for="vacancy in vacancies"
            :key="vacancy.id"
            :vacancy="vacancy"
            show-description
          >
            <template #actions>
              <NuxtLink
                :to="`/vacancies/${vacancy.id}`"
                class="btn btn-outline-primary btn-sm"
                title="Просмотр"
              >
                <EyeIcon style="width: 1rem; height: 1rem;" />
              </NuxtLink>
              <NuxtLink
                :to="`/vacancies/${vacancy.id}/edit`"
                class="btn btn-outline-secondary btn-sm"
                title="Редактировать"
              >
                <PencilIcon style="width: 1rem; height: 1rem;" />
              </NuxtLink>
              <button
                type="button"
                class="btn btn-outline-danger btn-sm"
                title="Удалить"
                @click="confirmDelete(vacancy)"
              >
                <TrashIcon style="width: 1rem; height: 1rem;" />
              </button>
            </template>
          </VacancyCard>
        </div>
      </div>

      <Pagination
        v-model:current-page="currentPage"
        :total-items="totalItems"
        :items-per-page="perPage"
        :items-text="pluralizeVacancies(totalItems)"
        @update:current-page="handlePageChange"
      />
    </div>

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :vacancy-title="vacancyToDelete?.title"
      :vacancy-id="vacancyToDelete?.id"
      @success="handleDeleteSuccess"
      @cancel="showDeleteModal = false"
    />
  </div>
</template>

<script setup>
import {
  PlusIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon,
} from '@heroicons/vue/24/outline'
import { pluralizeVacancies } from '~/utils/pluralize'

const { getVacancies } = useVacancies()

const showDeleteModal = ref(false)
const vacancyToDelete = ref(null)
const currentPage = ref(1)
const perPage = ref(10)

const currentFilters = ref({
  title: '',
  salary_from: '',
  salary_to: '',
  sort_by: 'created_at',
  sort_order: 'desc'
})

const queryParams = computed(() => ({
  ...currentFilters.value,
  page: currentPage.value,
  per_page: perPage.value
}))

const { data: vacanciesData, pending: loading, error, refresh } = await useLazyAsyncData(
  'vacancies',
  () => getVacancies(queryParams.value),
  {
    default: () => ({ data: [], meta: { total: 0, current_page: 1, last_page: 1, per_page: 10, from: 0, to: 0 } }),
    watch: [queryParams]
  }
)

const vacancies = computed(() => vacanciesData.value?.data || [])
const totalItems = computed(() => vacanciesData.value?.meta?.total || 0)

const handleFiltersChange = (newFilters) => {
  currentFilters.value = newFilters
  currentPage.value = 1
}

const handlePageChange = (page) => {
  currentPage.value = page
}

const confirmDelete = (vacancy) => {
  vacancyToDelete.value = vacancy
  showDeleteModal.value = true
}

const handleDeleteSuccess = async () => {
  showDeleteModal.value = false
  vacancyToDelete.value = null
  await refresh()
}

useHead({
  title: 'Вакансии - BestVacancy'
})
</script>