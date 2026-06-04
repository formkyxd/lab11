# CI/CD Pipeline — Городская транспортная сеть

## Обзор

Пайплайн запускается при любом `push` или `pull request` в ветки:
`main`, `master`, `develop`, `dev`, `uat`, `qa`

## Шаги пайплайна

### 1. Tests
- Запускает все тесты Laravel через `php artisan test`
- Использует `.env.ci` (SQLite in-memory, debug off)
- **Gate:** пайплайн падает если покрытие < 50% или любой тест не прошёл

### 2. Static Analysis (PHPStan / Larastan)
- Запускается после успешных тестов
- Уровень анализа: 5
- **Gate:** пайплайн падает при любой ошибке (не предупреждении)

### 3. Linting (Laravel Pint, пресет Laravel)
- Запускается после успешных тестов
- Для долгоживущих веток (`main`, `master`, `develop`, `dev`, `uat`, `qa`): запуск в `--test` режиме (без авто-исправления)
- Для остальных веток: авто-исправление кода
- **Gate:** пайплайн падает при любом нарушении правила линтера

### 4. Deploy Simulation
Запускается только если все предыдущие шаги успешны.

| Ветка | Окружение | Env файл |
|-------|-----------|----------|
| `develop` / `dev` | Development | `.env.dev` |
| `uat` / `qa` | UAT | `.env.uat` |
| `main` / `master` | Production | `.env.prod` |

Для `main`/`master` — требуется **ручной аппрув** через GitHub Environment `production`.

## Env файлы

| Файл | Назначение |
|------|-----------|
| `.env.dev` | Разработка — debug on, БД lab11_dev |
| `.env.uat` | UAT — debug off, БД lab11_uat |
| `.env.prod` | Продакшн — debug off, БД lab11_prod |
| `.env.ci` | CI — SQLite in-memory, debug off |
| `.env` | Не попадает в репозиторий (в .gitignore) |

## Настройка ручного аппрува для продакшна

1. GitHub → Settings → Environments → New environment → `production`
2. Включить "Required reviewers" → добавить себя
3. Шаг `deploy-prod` будет ждать аппрува перед выполнением

## Установка зависимостей для локального запуска

```bash
composer require --dev larastan/larastan
composer require --dev laravel/pint
```

## Запуск локально

```bash
# Тесты с покрытием
php artisan test --coverage --min=50

# PHPStan
composer analyse

# Pint (проверка без исправления)
composer lint

# Pint (авто-исправление)
composer lint:fix
```
