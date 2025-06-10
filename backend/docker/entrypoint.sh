#!/bin/bash
set -e

echo "Starting backend initialization..."

# Проверяем, существует ли vendor directory
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "Installing Composer dependencies..."

    # Обновляем пакеты
    echo "Running composer update..."
    composer update --prefer-dist

    # Устанавливаем зависимости (создаёт cookie validation key и другие настройки)
    echo "Running composer install..."
    composer install --prefer-dist
else
    echo "Composer dependencies already installed"
fi


# Выполняем миграции
echo "Running database migrations..."
php yii migrate --interactive=0 2>/dev/null || echo "Migrations failed or no migrations to run"

echo "Backend initialization completed!"

# Запускаем Apache
echo "Starting Apache server..."
exec apache2-foreground
