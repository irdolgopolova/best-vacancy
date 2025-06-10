<template>
  <div class="app-layout">
    <aside class="sidebar">
      <div class="sidebar-content">
        <SidebarBrand />
        <NavigationMenu />
        <QuickActions />
      </div>
    </aside>

    <main class="main-content">
      <slot />
    </main>
  </div>
</template>

<script setup>
</script>

<style scoped>
.app-layout {
  min-height: 100vh;
  display: flex;
}

.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 16rem;
  height: 100vh;
  z-index: 1000;
  background: white;
  border-right: 1px solid var(--bs-border-color);
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-content {
  display: flex;
  flex-direction: column;
  height: 100%;
  padding: 1.5rem 0;
  overflow-y: auto;
}

.main-content {
  flex: 1;
  margin-left: 16rem;
  padding: 1.5rem;
  min-width: 0;
}
</style>