<template>
  <div class="container-fluid">
    <LoadingSpinner v-if="dataLoading" text="Загрузка вакансии..." />

    <div v-else>
      <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="d-flex align-items-center">
          <div class="icon-bg-primary me-3">
            <BriefcaseIcon style="width: 1.5rem; height: 1.5rem;" />
          </div>
          <div>
            <h1 class="h2 fw-bold mb-2">
              {{ vacancy?.title }}
            </h1>
            <div class="d-flex align-items-center gap-4">
              <div class="d-flex align-items-center text-muted">
                <CurrencyDollarIcon style="width: 1rem; height: 1rem;" class="me-1" />
                <span class="fw-semibold text-primary">
                  {{ formatSalary(vacancy.salary) }}
                </span>
              </div>
              <div v-if="vacancy.experience" class="d-flex align-items-center text-muted">
                <ClockIcon style="width: 1rem; height: 1rem;" class="me-1" />
                <span>{{ vacancy.experience }}</span>
              </div>
              <div class="d-flex align-items-center text-muted">
                <CalendarIcon style="width: 1rem; height: 1rem;" class="me-1" />
                <span>{{ formatDate(vacancy.created_at) }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2">
          <NuxtLink :to="`/vacancies/${vacancy.id}/edit`" class="btn btn-primary">
            <PencilIcon style="width: 1rem; height: 1rem;" class="me-2" />
            Редактировать
          </NuxtLink>
          <button @click="showDeleteModal = true" class="btn btn-danger">
            <TrashIcon style="width: 1rem; height: 1rem;" class="me-2" />
            Удалить
          </button>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <h2 class="h5 mb-0">
                Описание вакансии
              </h2>
            </div>
            <div class="card-body">
              <p class="mb-0" style="white-space: pre-wrap;">{{ vacancy.description }}</p>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <h3 class="h6 mb-0">Детали вакансии</h3>
            </div>
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-6">
                  <span class="text-muted small">ID:</span>
                </div>
                <div class="col-6 text-end">
                  <span class="fw-semibold">{{ vacancy.id }}</span>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-6">
                  <span class="text-muted small">Зарплата:</span>
                </div>
                <div class="col-6 text-end">
                  <span class="fw-semibold text-primary">
                    {{ formatSalary(vacancy.salary) }}
                  </span>
                </div>
              </div>

              <div v-if="vacancy.experience" class="row mb-3">
                <div class="col-6">
                  <span class="text-muted small">Опыт:</span>
                </div>
                <div class="col-6 text-end">
                  <span class="fw-semibold">{{ vacancy.experience }}</span>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-6">
                  <span class="text-muted small">Создана:</span>
                </div>
                <div class="col-6 text-end">
                  <span class="fw-semibold small">
                    {{ formatDate(vacancy.created_at) }}
                  </span>
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <span class="text-muted small">Обновлена:</span>
                </div>
                <div class="col-6 text-end">
                  <span class="fw-semibold small">
                    {{ formatDate(vacancy.updated_at) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :vacancy-title="vacancy?.title"
      :vacancy-id="vacancy?.id"
      @success="handleDeleteSuccess"
      @cancel="showDeleteModal = false"
    />
  </div>
</template>

<script setup>
import {
  BriefcaseIcon,
  PencilIcon,
  TrashIcon,
  CurrencyDollarIcon,
  CalendarIcon,
  ClockIcon,
} from '@heroicons/vue/24/outline'

const route = useRoute()
const { getVacancy, formatSalary, formatDate } = useVacancies()
const showDeleteModal = ref(false)
const vacancyId = computed(() => parseInt(route.params.id))

const { data: vacancy, pending: dataLoading } = await useLazyAsyncData(
  `vacancy-view-${vacancyId.value}`,
  () => getVacancy(vacancyId.value, 'experience,updated_at')
)

const handleDeleteSuccess = async () => {
  showDeleteModal.value = false
  await navigateTo('/vacancies')
}

useHead({
  title: computed(() =>
    vacancy.value ?
      `${vacancy.value.title} - BestVacancy` :
      'Вакансия - BestVacancy'
  )
})

definePageMeta({
  layout: 'default'
})
</script>