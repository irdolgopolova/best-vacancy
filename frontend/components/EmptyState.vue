<template>
  <div class="text-center py-5">
    <h3 class="h6 mb-2">{{ title }}</h3>
    <p class="text-muted mb-3">
      {{ description }}
    </p>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
})
</script>