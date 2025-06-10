<template>
  <div class="hero-gradient mb-4 fade-in">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h1 class="display-4 fw-bold mb-2">
          {{ title }}
        </h1>
        <p v-if="subtitle" class="fs-5 opacity-75">
          {{ subtitle }}
        </p>
      </div>
      <div v-if="$slots.actions">
        <slot name="actions" />
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: null
  }
})
</script>