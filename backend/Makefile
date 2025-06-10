# Yii2 Backend Management Commands

# Переменные
COMPOSE_FILE = docker-compose.yml
SERVICE_NAME = php

# Помощь
help:
	@echo "Доступные команды:"
	@echo "  make up          - Запустить контейнеры"
	@echo "  make down        - Остановить контейнеры"
	@echo "  make logs        - Показать логи"
	@echo "  make shell       - Войти в контейнер PHP"
	@echo ""
	@echo "Миграции:"
	@echo "  make migrate-create name=migration_name  - Создать новую миграцию"
	@echo "  make migrate-up                         - Применить все миграции"
	@echo "  make migrate-down count=1               - Откатить миграции (по умолчанию 1)"
	@echo "  make migrate-status                     - Статус миграций"
	@echo ""
	@echo "Другие команды:"
	@echo "  make composer-install  - Установить зависимости"
	@echo "  make composer-update   - Обновить зависимости"

# Docker команды
up:
	docker-compose -f $(COMPOSE_FILE) up -d

down:
	docker-compose -f $(COMPOSE_FILE) down

logs:
	docker-compose -f $(COMPOSE_FILE) logs -f

shell:
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) bash

# Миграции
migrate-create:
	@if [ -z "$(name)" ]; then \
		echo "Ошибка: Укажите имя миграции. Пример: make migrate-create name=create_users_table"; \
		exit 1; \
	fi
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) php yii migrate/create $(name)

migrate-up:
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) php yii migrate --interactive=0

migrate-down:
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) php yii migrate/down $(or $(count),1) --interactive=0

migrate-status:
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) php yii migrate/history

# Composer
composer-install:
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) composer install

composer-update:
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) composer update

# Дополнительные команды
cache-flush:
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) php yii cache/flush-all

# Права доступа (для Linux/macOS)
fix-permissions:
	docker-compose -f $(COMPOSE_FILE) exec $(SERVICE_NAME) chown -R www-data:www-data /app/runtime /app/web/assets

.PHONY: help up down logs shell migrate-create migrate-up migrate-down migrate-status composer-install composer-update cache-flush fix-permissions 