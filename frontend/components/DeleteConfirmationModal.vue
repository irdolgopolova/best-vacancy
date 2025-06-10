<template>
  <div>
    <div
      class="modal fade"
      :class="{ show: show }"
      :style="{ display: show ? 'block' : 'none' }"
      tabindex="-1"
      @keydown.esc="handleCancel"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Удалить вакансию</h5>
            <button
              type="button"
              class="btn-close"
              @click="handleCancel"
              aria-label="Закрыть"
            ></button>
          </div>
          <div class="modal-body">
            <div class="d-flex align-items-center mb-3">
              <div class="icon-bg-danger me-3">
                <ExclamationTriangleIcon class="icon-lg" />
              </div>
              <div>
                <h6 class="mb-1">Подтвердите удаление</h6>
                <p class="mb-0 text-muted">
                  Вы уверены, что хотите удалить вакансию {{vacancyTitle}}? Это действие нельзя отменить.
                </p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="handleCancel"
              :disabled="loading"
            >
              Отмена
            </button>
            <button
              type="button"
              class="btn btn-danger"
              @click="handleConfirm"
              :disabled="loading"
            >
              <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
              {{ loading ? "Удаление..." : "Удалить" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="show"
      class="modal-backdrop fade show"
      @click="handleCancel"
    ></div>
  </div>
</template>

<script setup>
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  vacancyTitle: {
    type: String,
    default: ''
  },
  vacancyId: {
    type: Number,
    required: false
  }
})

const emit = defineEmits(['success', 'cancel'])

const { loading, deleteVacancyAction } = useVacancies()

const handleConfirm = async () => {
  if (props.vacancyId) {
    const result = await deleteVacancyAction(props.vacancyId)
    if (result.success) {
      emit('success')
    }
  } else {
    emit('confirm')
  }
}

const handleCancel = () => {
  emit('cancel')
}

onMounted(() => {
  const handleEscape = (event) => {
    if (event.key === 'Escape' && props.show) {
      handleCancel()
    }
  }

  document.addEventListener('keydown', handleEscape)

  onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape)
  })
})
</script>

<style scoped>
.icon-lg {
  width: 1.5rem;
  height: 1.5rem;
}

.modal.fade .modal-dialog {
  transition: transform 0.3s ease-out;
  transform: translate(0, -50px);
}

.modal.show .modal-dialog {
  transform: none;
}

.modal-backdrop {
  transition: opacity 0.15s linear;
}

.btn-close:focus {
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}
</style>