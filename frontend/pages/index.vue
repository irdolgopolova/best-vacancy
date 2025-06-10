<template>
  <div class="container-fluid">
    <HeroBanner title="Добро пожаловать в BestVacancy!"/>

    <StatsCards :stats="stats" />

    <div class="card mb-4 fade-in">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">
          Последние вакансии
        </h2>
        <NuxtLink
          to="/vacancies"
          class="btn btn-outline-primary btn-sm"
        >
          Посмотреть все
        </NuxtLink>
      </div>
      <div class="card-body">
        <LoadingSpinner v-if="loading" />
        <EmptyState
          v-else-if="!recentVacancies || recentVacancies.length === 0"
          title="Нет вакансий"
          description="Создайте первую вакансию для начала работы"
        />
        <div v-else>
          <VacancyCard
            v-for="vacancy in recentVacancies"
            :key="vacancy.id"
            :vacancy="vacancy"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { getVacancies, getStats } = useVacancies()

const { data: stats, pending: statsLoading } = await useLazyAsyncData('dashboard-stats', () => getStats())
const { data: allVacancies, pending: vacanciesLoading } = await useLazyAsyncData('dashboard-vacancies', () => getVacancies({ per_page: 5, sort_by: 'created_at', sort_order: 'desc' }))

const recentVacancies = computed(() => allVacancies.value?.data || [])
const loading = computed(() => statsLoading.value || vacanciesLoading.value)

useHead({
  title: 'Дашборд - BestVacancy'
})
</script>