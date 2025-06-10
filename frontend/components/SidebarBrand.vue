<template>
  <div class="sidebar-header">
    <NuxtLink to="/" class="brand-link">
      <div class="icon-bg-primary me-3">
        <BriefcaseIcon class="brand-icon" />
      </div>
      <h1 class="brand-title">BestVacancy</h1>
    </NuxtLink>
  </div>
</template>

<script setup>
import { BriefcaseIcon } from '@heroicons/vue/24/outline'
</script>

<style scoped>
.sidebar-header {
  padding: 0 1.5rem 1.5rem;
  border-bottom: 1px solid var(--bs-border-color);
  margin-bottom: 1.5rem;
}

.brand-link {
  display: flex;
  align-items: center;
  text-decoration: none;
  transition: color 0.2s ease;
}

.brand-link:hover {
  color: var(--bs-primary);
}

.brand-icon {
  width: 1.25rem;
  height: 1.25rem;
}

.brand-title {
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0;
  color: var(--bs-dark);
}
</style>