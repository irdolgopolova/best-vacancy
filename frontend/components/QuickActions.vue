<template>
  <div class="quick-actions">
    <h6 class="quick-actions-title">Быстрые действия</h6>
    <div class="quick-actions-list">
      <NuxtLink
        to="/vacancies/create"
        class="action-link"
        @click="handleNavigate"
      >
        <div class="action-icon primary">
          <PlusIcon />
        </div>
        <div class="action-content">
          <div class="action-title">Создать вакансию</div>
          <div class="action-subtitle">Добавить новую</div>
        </div>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup>
import { PlusIcon, ListBulletIcon } from '@heroicons/vue/24/outline'

const emit = defineEmits(['navigate'])

const handleNavigate = () => {
  emit('navigate')
}
</script>

<style scoped>
.quick-actions {
  padding: 0 1rem;
  margin-top: 1.5rem;
}

.quick-actions-title {
  text-transform: uppercase;
  color: var(--bs-secondary);
  font-size: 0.75rem;
  font-weight: 600;
  margin-bottom: 1rem;
  padding: 0 0.75rem;
}

.quick-actions-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.action-link {
  display: flex;
  align-items: center;
  padding: 0.75rem;
  border: 1px solid var(--bs-border-color);
  border-radius: 0.5rem;
  text-decoration: none;
  color: var(--bs-dark);
  transition: all 0.2s ease;
}

.action-link:hover {
  background-color: var(--bs-light);
  border-color: var(--bs-primary);
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  color: var(--bs-dark);
}

.action-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border-radius: 0.375rem;
  margin-right: 0.75rem;
  flex-shrink: 0;
}

.action-icon svg {
  width: 1rem;
  height: 1rem;
  color: white;
}

.action-icon.primary {
  background-color: var(--bs-primary);
}

.action-icon.success {
  background-color: var(--bs-success);
}

.action-content {
  flex: 1;
}

.action-title {
  font-weight: 600;
  font-size: 0.875rem;
  margin-bottom: 0.125rem;
}

.action-subtitle {
  font-size: 0.75rem;
  color: var(--bs-secondary);
}
</style>