/**
 * Функция для склонения русских слов в зависимости от числа
 * @param {number} count - количество
 * @param {string} one - форма для 1 (вакансия)
 * @param {string} few - форма для 2-4 (вакансии)
 * @param {string} many - форма для 5+ (вакансий)
 * @returns {string} правильная форма слова
 */
export const pluralize = (count, one, few, many) => {
  const absCount = Math.abs(count)
  const lastDigit = absCount % 10
  const lastTwoDigits = absCount % 100

  if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
    return many
  }

  if (lastDigit === 1) {
    return one
  }

  if (lastDigit >= 2 && lastDigit <= 4) {
    return few
  }

  return many
}

/**
 * Готовые функции для часто используемых слов
 */
export const pluralizeVacancies = (count) => {
  return pluralize(count, 'вакансия', 'вакансии', 'вакансий')
}

export const pluralizeUsers = (count) => {
  return pluralize(count, 'пользователь', 'пользователя', 'пользователей')
}

export const pluralizeRecords = (count) => {
  return pluralize(count, 'запись', 'записи', 'записей')
}