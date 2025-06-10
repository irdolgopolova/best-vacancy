<template>
  <div class="container-fluid">
    <LoadingSpinner v-if="dataLoading" text="Загрузка данных вакансии..." />

    <ErrorState
      v-else-if="error"
      message="Не удалось загрузить данные вакансии"
      back-url="/vacancies"
      back-text="Вернуться к списку"
      @retry="refresh"
    />

    <div v-else>
      <div class="card mb-4">
        <div class="card-body">
          <VacancyForm
            mode="edit"
            :initial-data="vacancy"
            :vacancy-id="vacancyId"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const { getVacancy } = useVacancies()

const vacancyId = computed(() => parseInt(route.params.id))

const { data: vacancy, pending: dataLoading, error, refresh } = await useLazyAsyncData(
  `vacancy-edit-${vacancyId.value}`,
  () => getVacancy(vacancyId.value)
)

useHead({
  title: computed(() =>
    vacancy.value ?
    `Редактирование "${vacancy.value.title}" - BestVacancy` :
    'Редактирование вакансии - BestVacancy'
  )
})

definePageMeta({
  layout: 'default'
})
</script>