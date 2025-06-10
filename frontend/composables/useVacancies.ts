export interface Vacancy {
  id: number
  title: string
  description: string
  salary: number
  experience: string
  created_at: string
  updated_at: string
}

export interface VacancyForm {
  title: string
  description: string
  salary: number
  experience?: string
}

export interface VacancyStats {
  total_vacancies: number
  average_salary: number
  min_salary: number
  max_salary: number
}

export interface VacancyFilters {
  title?: string
  salary_from?: string
  salary_to?: string
  per_page?: number
  page?: number
  sort_by?: string
  sort_order?: string
}

export interface PaginationMeta {
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
}

export interface PaginatedVacancies {
  data: Vacancy[]
  meta: PaginationMeta
}

export interface ActionResult {
  success: boolean
  error?: any
}

export const useVacancies = () => {
  const config = useRuntimeConfig()
  const loading = ref<boolean>(false)

  // Функция для получения правильного API URL
  const getApiUrl = (): string => {
    // На сервере используем внутренний Docker URL, на клиенте - localhost
    return import.meta.server ? (config.apiBase as string) : config.public.apiBase
  }

  const showSuccessToast = (title: string, message: string): void => {
    if (import.meta.client && window.$toast) {
      window.$toast.success(title, message)
    }
  }

  const showErrorToast = (title: string, message: string): void => {
    if (import.meta.client && window.$toast) {
      window.$toast.error(title, message)
    }
  }

  const handleApiError = (error: any, defaultMessage: string): never => {
    const errorMessage = error.data?.message || error.message || defaultMessage
    throw createError({
      statusCode: error.statusCode || 400,
      message: errorMessage
    })
  }

  const getVacancies = async (customFilters?: VacancyFilters): Promise<PaginatedVacancies> => {
    const params = new URLSearchParams()

    Object.entries(customFilters || {}).forEach(([key, value]) => {
      if (value != null && value !== '') {
        params.append(key, value.toString())
      }
    })

    try {
      const data = await $fetch<PaginatedVacancies>(
        `${getApiUrl()}/api/vacancies?${params}`,
        {
          method: 'GET',
        }
      )
      return data
    } catch (error) {
      throw createError({
        statusCode: 500,
        message: 'Ошибка при получении вакансий'
      })
    }
  }

  const getVacancy = async (id: number, fields?: string): Promise<Vacancy> => {
    try {
      const url = fields
        ? `${getApiUrl()}/api/vacancies/${id}?fields=${fields}`
        : `${getApiUrl()}/api/vacancies/${id}`
      return await $fetch<Vacancy>(url)
    } catch (error: any) {
      return handleApiError(error, 'Вакансия не найдена')
    }
  }

  const createVacancy = async (vacancyData: VacancyForm): Promise<Vacancy> => {
    try {
      return await $fetch<Vacancy>(`${getApiUrl()}/api/vacancies`, {
        method: 'POST',
        body: vacancyData
      })
    } catch (error: any) {
      return handleApiError(error, 'Ошибка при создании вакансии')
    }
  }

  const updateVacancy = async (id: number, vacancyData: VacancyForm): Promise<Vacancy> => {
    try {
      return await $fetch<Vacancy>(`${getApiUrl()}/api/vacancies/${id}`, {
        method: 'PUT',
        body: vacancyData
      })
    } catch (error: any) {
      return handleApiError(error, 'Ошибка при обновлении вакансии')
    }
  }

  const deleteVacancy = async (id: number): Promise<void> => {
    try {
      await $fetch(`${getApiUrl()}/api/vacancies/${id}`, {
        method: 'DELETE'
      })
    } catch (error: any) {
      handleApiError(error, 'Ошибка при удалении вакансии')
    }
  }

  const getStats = async (): Promise<VacancyStats> => {
    try {
      return await $fetch<VacancyStats>(`${getApiUrl()}/api/vacancies/stats`)
    } catch (error: any) {
      return handleApiError(error, 'Ошибка при получении статистики')
    }
  }

  const formatSalary = (salary: number): string => {
    if (!salary) return '—'
    return new Intl.NumberFormat('ru-RU', {
      style: 'currency',
      currency: 'RUB',
      minimumFractionDigits: 0,
    }).format(salary)
  }

  const formatDate = (date: string): string => {
    if (!date) return '—'
    return new Intl.DateTimeFormat('ru-RU', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    }).format(new Date(date))
  }

  const handleAction = async (
    actionFn: () => Promise<any>,
    successMessage: string,
    errorMessage: string = 'Произошла ошибка',
    redirectUrl: string = '/vacancies'
  ): Promise<ActionResult> => {
    loading.value = true

    try {
      await actionFn()
      showSuccessToast('Успешно!', successMessage)
      await navigateTo(redirectUrl)
      return { success: true }
    } catch (error: any) {
      console.error('Ошибка действия:', error)
      const message = error.message || errorMessage
      showErrorToast('Ошибка', message)
      return { success: false, error }
    } finally {
      loading.value = false
    }
  }

  const createVacancyAction = async (formData: VacancyForm, redirectUrl = '/vacancies') =>
    handleAction(
      () => createVacancy(formData),
      'Вакансия создана',
      'Не удалось создать вакансию',
      redirectUrl
    )

  const updateVacancyAction = async (id: number, formData: VacancyForm, redirectUrl = '/vacancies') =>
    handleAction(
      () => updateVacancy(id, formData),
      'Вакансия успешно обновлена',
      'Не удалось обновить вакансию',
      redirectUrl
    )

  const deleteVacancyAction = async (id: number, redirectUrl = '/vacancies') =>
    handleAction(
      () => deleteVacancy(id),
      'Вакансия удалена',
      'Не удалось удалить вакансию',
      redirectUrl
    )

  const cancelAction = (redirectUrl: string = '/vacancies'): void => {
    navigateTo(redirectUrl)
  }

  return {
    loading: readonly(loading),
    getVacancies,
    getVacancy,
    getStats,
    createVacancyAction,
    updateVacancyAction,
    deleteVacancyAction,
    cancelAction,
    formatSalary,
    formatDate
  }
}