<template>
  <div class="row g-4 mb-4">
    <StatCard
      :icon="BriefcaseIcon"
      title="Всего вакансий"
      :value="stats?.total_vacancies || 0"
      icon-bg-class="icon-bg-primary"
    />

    <StatCard
      :icon="CurrencyDollarIcon"
      title="Средняя зарплата"
      :value="formatSalary(stats?.average_salary)"
      icon-bg-class="icon-bg-success"
    />

    <StatCard
      :icon="ArrowTrendingUpIcon"
      title="Максимальная зарплата"
      :value="formatSalary(stats?.max_salary)"
      icon-bg-class="icon-bg-warning"
    />

    <StatCard
      :icon="ArrowTrendingDownIcon"
      title="Минимальная зарплата"
      :value="formatSalary(stats?.min_salary)"
      icon-bg-class="icon-bg-danger"
    />
  </div>
</template>

<script setup>
import {
  BriefcaseIcon,
  CurrencyDollarIcon,
  ArrowTrendingUpIcon,
  ArrowTrendingDownIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({})
  },
})

const { formatSalary } = useVacancies()
</script>