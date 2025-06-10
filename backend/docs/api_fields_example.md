# Использование параметра fields

## Описание

Параметр `fields` позволяет запрашивать только нужные поля, включая опциональные.

## Доступные поля

### Стандартные поля (возвращаются по умолчанию):
- `id` - ID вакансии
- `title` - Название вакансии
- `description` - Описание вакансии
- `salary` - Зарплата
- `created_at` - Дата создания
- `updated_at` - Дата обновления

### Опциональные поля (только по запросу):
- `experience` - Требуемый опыт работы

## Примеры использования

### Получить все стандартные поля (по умолчанию)
```bash
GET /api/vacancies
```

### Получить только определенные поля
```bash
GET /api/vacancies?fields=id,title,salary
```

### Получить стандартные поля + опыт
```bash
GET /api/vacancies?fields=id,title,salary,experience
```

### Получить только опыт и название
```bash
GET /api/vacancies?fields=title,experience
```

### Для конкретной вакансии
```bash
GET /api/vacancies/1?fields=id,title,salary,experience
```

## Примеры ответов

### Без параметра fields (стандартные поля)
```json
{
  "data": [
    {
      "id": 1,
      "title": "Frontend разработчик",
      "description": "Описание вакансии...",
      "salary": 100000,
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    }
  ]
}
```

### С параметром fields=id,title,salary,experience
```json
{
  "data": [
    {
      "id": 1,
      "title": "Frontend разработчик",
      "salary": 100000,
      "experience": "1-3 года"
    }
  ]
}
```