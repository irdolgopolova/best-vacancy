<template>
  <form @submit.prevent="handleSubmit">
    <div class="mb-3">
      <label for="title" class="form-label">
        Название вакансии *
      </label>
      <input
        id="title"
        v-model="form.title"
        type="text"
        placeholder="Например: Frontend Developer"
        class="form-control"
        :class="{ 'is-invalid': errors.title }"
        required
      />
      <div v-if="errors.title" class="invalid-feedback">
        {{ errors.title }}
      </div>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">
        Описание вакансии *
      </label>
      <textarea
        id="description"
        v-model="form.description"
        rows="6"
        placeholder="Подробное описание обязанностей, требований и условий работы..."
        class="form-control"
        :class="{ 'is-invalid': errors.description }"
        required
      ></textarea>
      <div v-if="errors.description" class="invalid-feedback">
        {{ errors.description }}
      </div>
      <div class="form-text">
        Опишите обязанности, требования к кандидату, условия работы и преимущества
      </div>
    </div>

    <div class="mb-3">
      <label for="salary" class="form-label">
        Зарплата (в рублях) *
      </label>
      <div class="input-group">
        <input
          id="salary"
          v-model="form.salary"
          type="number"
          min="1000"
          max="10000000"
          step="1000"
          placeholder="75000"
          class="form-control"
          :class="{ 'is-invalid': errors.salary }"
          required
        />
        <div v-if="errors.salary" class="invalid-feedback">
          {{ errors.salary }}
        </div>
      </div>
      <div class="form-text">
        Укажите размер заработной платы в рублях
      </div>
    </div>

    <div class="mb-4">
      <label for="experience" class="form-label">
        Требуемый опыт работы
      </label>
      <select
        id="experience"
        v-model="form.experience"
        class="form-select"
        :class="{ 'is-invalid': errors.experience }"
      >
        <option value="Не требуется">Не требуется</option>
        <option value="От 1 года">От 1 года</option>
        <option value="1-3 года">1-3 года</option>
        <option value="3-6 лет">3-6 лет</option>
        <option value="Более 6 лет">Более 6 лет</option>
      </select>
      <div v-if="errors.experience" class="invalid-feedback">
        {{ errors.experience }}
      </div>
      <div class="form-text">
        Укажите минимальный опыт работы для данной позиции
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
      <button
        type="button"
        @click="handleCancel"
        class="btn btn-secondary"
        :disabled="loading"
      >
        <XMarkIcon style="width: 1rem; height: 1rem;" class="me-2" />
        Отмена
      </button>

      <div class="d-flex gap-2">
        <button
          v-if="mode === 'edit'"
          type="button"
          @click="handleDelete"
          class="btn btn-danger"
          :disabled="loading"
        >
          <TrashIcon style="width: 1rem; height: 1rem;" class="me-2" />
          Удалить
        </button>

        <button
          type="submit"
          class="btn btn-primary"
          :disabled="loading || !isFormValid"
        >
          <div v-if="loading" class="spinner-border spinner-border-sm me-2" role="status">
            <span class="visually-hidden">Загрузка...</span>
          </div>
          <CheckIcon v-else style="width: 1rem; height: 1rem;" class="me-2" />
          {{ mode === 'create' ? 'Создать вакансию' : 'Сохранить изменения' }}
        </button>
      </div>
    </div>

    <DeleteConfirmationModal
      :show="showDeleteModal"
      :vacancy-title="props.initialData?.title"
      :loading="loading"
      @confirm="confirmDelete"
      @cancel="showDeleteModal = false"
    />
  </form>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import {
  CurrencyDollarIcon,
  XMarkIcon,
  CheckIcon,
  TrashIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
  mode: {
    type: String,
    required: true,
    validator: (value) => ['create', 'edit'].includes(value)
  },
  initialData: {
    type: Object,
    default: () => ({})
  },
  vacancyId: {
    type: Number,
    required: false
  }
})

const emit = defineEmits(['success', 'cancel'])

const { loading, createVacancyAction, updateVacancyAction, deleteVacancyAction, cancelAction } = useVacancies()

const form = ref({
  title: '',
  description: '',
  salary: '',
  experience: 'Не требуется'
})

const errors = ref({})
const showDeleteModal = ref(false)

const initializeForm = () => {
  if (props.mode === 'edit' && props.initialData) {
    form.value = {
      title: props.initialData.title || '',
      description: props.initialData.description || '',
      salary: props.initialData.salary?.toString() || '',
      experience: props.initialData.experience || 'Не требуется'
    }
  } else {
    form.value = {
      title: '',
      description: '',
      salary: '',
      experience: 'Не требуется'
    }
  }
  errors.value = {}
}

watch(() => props.initialData, initializeForm, { immediate: true })

const isFormValid = computed(() => {
  return form.value.title.trim() &&
         form.value.description.trim() &&
         form.value.salary &&
         parseInt(form.value.salary) >= 1000 &&
         parseInt(form.value.salary) <= 10000000
})

const validateField = (field) => {
  switch (field) {
    case 'title':
      if (!form.value.title.trim()) {
        errors.value.title = 'Название вакансии обязательно'
      } else if (form.value.title.length < 3) {
        errors.value.title = 'Название должно содержать минимум 3 символа'
      } else {
        delete errors.value.title
      }
      break

    case 'description':
      if (!form.value.description.trim()) {
        errors.value.description = 'Описание вакансии обязательно'
      } else if (form.value.description.length < 10) {
        errors.value.description = 'Описание должно содержать минимум 10 символов'
      } else {
        delete errors.value.description
      }
      break

    case 'salary':
      if (!form.value.salary) {
        errors.value.salary = 'Зарплата обязательна'
      } else if (parseInt(form.value.salary) < 1000) {
        errors.value.salary = 'Зарплата должна быть минимум 1 000 рублей'
      } else if (parseInt(form.value.salary) > 10000000) {
        errors.value.salary = 'Зарплата не может превышать 10 000 000 рублей'
      } else {
        delete errors.value.salary
      }
      break
  }
}

const validateForm = () => {
  validateField('title')
  validateField('description')
  validateField('salary')

  return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
  if (validateForm()) {
    const formData = {
      title: form.value.title.trim(),
      description: form.value.description.trim(),
      salary: parseInt(form.value.salary),
      experience: form.value.experience
    }

    if (props.mode === 'edit' && props.vacancyId) {
      const result = await updateVacancyAction(props.vacancyId, formData)
      if (result.success) {
        emit('success')
      }
    } else if (props.mode === 'create') {
      const result = await createVacancyAction(formData)
      if (result.success) {
        emit('success')
      }
    }
  }
}

const handleCancel = () => {
  cancelAction()
}

const handleDelete = () => {
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  showDeleteModal.value = false
  if (props.mode === 'edit' && props.vacancyId) {
    const result = await deleteVacancyAction(props.vacancyId)
    if (result.success) {
      emit('success')
    }
  }
}

watch(() => form.value.title, () => validateField('title'))
watch(() => form.value.description, () => validateField('description'))
watch(() => form.value.salary, () => validateField('salary'))
</script>