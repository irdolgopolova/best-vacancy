<template>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card fade-in">
      <div class="d-flex align-items-center">
        <div class="me-3" :class="iconBgClass">
          <component :is="icon" style="width: 2rem; height: 2rem;" />
        </div>
        <div>
          <p class="text-muted small mb-1">
            {{ title }}
          </p>
          <p class="fw-bold mb-0 fs-4">
            {{ value }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  icon: {
    type: [Object, Function],
    required: true
  },
  title: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  iconBgClass: {
    type: String,
    default: 'icon-bg-primary'
  },
})
</script>