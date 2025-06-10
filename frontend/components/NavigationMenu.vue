<template>
  <nav class="sidebar-nav">
    <div class="nav flex-column">
      <NuxtLink
        to="/"
        class="nav-link"
        :class="isActive('/') ? 'active' : ''"
        @click="handleNavigate"
      >
        <HomeIcon class="nav-icon" />
        Дашборд
      </NuxtLink>
      <NuxtLink
        to="/vacancies"
        class="nav-link"
        :class="isActive('/vacancies') ? 'active' : ''"
        @click="handleNavigate"
      >
        <BriefcaseIcon class="nav-icon" />
        Вакансии
      </NuxtLink>
    </div>
  </nav>
</template>

<script setup>
import { HomeIcon, BriefcaseIcon } from '@heroicons/vue/24/outline'

const emit = defineEmits(['navigate'])
const route = useRoute()

const isActive = (path) => {
  if (path === '/') {
    return route.path === '/'
  }
  return route.path.startsWith(path)
}

const handleNavigate = () => {
  emit('navigate')
}
</script>

<style scoped>
.sidebar-nav {
  flex: 1;
  padding: 0 1rem;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem;
  margin-bottom: 0.25rem;
  border-radius: 0.5rem;
  text-decoration: none;
  color: var(--bs-dark);
  transition: all 0.2s ease;
}

.nav-link:hover {
  background-color: var(--bs-light);
  color: var(--bs-primary);
}

.nav-link.active {
  background-color: var(--bs-primary);
  color: white;
}

.nav-icon {
  width: 1.25rem;
  height: 1.25rem;
  margin-right: 0.75rem;
}
</style>