# Test App - Система управления вакансиями

Полнофункциональное веб-приложение для управления вакансиями с современным стеком технологий.

## Стек технологий

### Backend
- **PHP 7.4** + **Yii2 Framework**
- **MySQL 8.0** для хранения данных
- RESTful API для взаимодействия с фронтендом

### Frontend
- **Nuxt.js 3** (Vue.js 3 + TypeScript)
- **Bootstrap 5** для UI
- **Pinia** для управления состоянием

## Быстрый старт

### Требования
- Docker и Docker Compose
- Git

### Запуск через Docker

1. **Клонируйте репозиторий:**
```bash
git clone <repository-url>
cd test_app
```

2. **Запустите все сервисы:**
```bash
docker-compose up -d
```

> **Примечание:** Backend автоматически выполнит:
> - Установку зависимостей Composer
> - Ожидание запуска базы данных
> - Выполнение миграций базы данных

3. **Откройте приложение:**
- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8084

## Доступные сервисы

| Сервис      | URL                   | Описание           |
| ----------- | --------------------- | ------------------ |
| Frontend    | http://localhost:3000 | Nuxt.js приложение |
| Backend API | http://localhost:8000 | Yii2 REST API      |
| phpMyAdmin  | http://localhost:8084 | Управление БД      |
| MySQL       | localhost:3307        | База данных        |

## Первый запуск

При первом запуске backend автоматически:

1. **Установит зависимости Composer** (`composer update` и `composer install`)
2. **Дождется запуска MySQL**
3. **Выполнит миграции базы данных**

Вы можете наблюдать за процессом инициализации:
```bash
docker-compose logs -f backend
```

## Разработка

### Логи сервисов
```bash
# Все сервисы
docker-compose logs -f

# Конкретный сервис
docker-compose logs -f frontend
docker-compose logs -f backend
```

### Остановка сервисов
```bash
# Остановить все сервисы
docker-compose down

# Остановить и удалить volumes
docker-compose down -v
```

## API Endpoints

### Вакансии
- `GET /api/vacancies` - Список вакансий с пагинацией и фильтрацией
- `GET /api/vacancies/{id}` - Детали вакансии
- `POST /api/vacancies` - Создание вакансии
- `PUT /api/vacancies/{id}` - Обновление вакансии
- `DELETE /api/vacancies/{id}` - Удаление вакансии
- `GET /api/vacancies/stats` - Статистика вакансий

### Параметры фильтрации
- `title` - Поиск по названию
- `salary_from` / `salary_to` - Диапазон зарплат
- `per_page` - Количество на странице
- `page` - Номер страницы
- `sort_by` - Поле сортировки
- `sort_order` - Направление сортировки (asc/desc)

## Структура проекта

```
test_app/
├── backend/           # Yii2 API
│   ├── controllers/   # Контроллеры
│   ├── models/       # Модели данных
│   ├── migrations/   # Миграции БД
│   └── config/       # Конфигурация
├── frontend/         # Nuxt.js приложение
│   ├── components/   # Vue компоненты
│   ├── pages/        # Страницы
│   ├── composables/  # Композаблы
│   └── assets/       # Статические ресурсы
└── docker-compose.yaml # Docker конфигурация
```

## База данных

### Подключение к MySQL
- **Host**: localhost
- **Port**: 3307
- **Database**: app_db
- **Username**: app_user
- **Password**: app_password

### Схема БД
- `vacancies` - Основная таблица вакансий
- Поля: id, title, description, salary, experience, created_at, updated_at

## Troubleshooting

### Проблемы с портами
Если порты заняты, измените их в `docker-compose.yaml`:
```yaml
ports:
  - "3001:3000"  # frontend
  - "8001:80"    # backend
  - "3308:3306"  # mysql
```

### Проблемы с правами доступа
```bash
# Для macOS/Linux
sudo chown -R $USER:$USER ./
```

### Пересоздание контейнеров
```bash
docker-compose down
docker-compose up --build -d
```
