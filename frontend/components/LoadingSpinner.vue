<template>
  <div class="d-flex justify-content-center py-4">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">{{ text }}</span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  text: {
    type: String,
    default: 'Загрузка...'
  }
})
</script>