# Веб-приложение «ChessEvent»

Шахматный портал с онлайн-игрой, турнирами, рейтингом ELO, VK-ботом и SEO-оптимизацией.
---

## 📌 Функционал

### 🧑 Пользователи
- Регистрация и авторизация (username, email, пароль)
- Восстановление пароля через VK-бота (привязка почты)
- Личный кабинет с рейтингом ELO, статистикой и историей партий

### ♟ Онлайн-игра
- Поиск соперника через WebSocket
- Шахматная доска с перетаскиванием фигур (chessboard.js + chess.js)
- Подсветка возможных ходов
- Чат с соперником
- Предложение ничьей, сдача партии
- Автоматическое определение мата, пата, повторений
- Сохранение всех ходов и истории партий

### 🏆 Турниры
- Создание турниров администратором
- Фильтрация по уровню (международный, федеральный, региональный, муниципальный, уровень учреждения)
- Регистрация на турнир, отмена регистрации
- Автоматическая жеребьёвка (швейцарская / круговая система)
- Ввод результатов, турнирная таблица

### 📨 VK-бот (Polling)
- Привязка email к VK для восстановления пароля
- Восстановление пароля с подтверждением по коду
- Подписка на турниры для получения уведомлений о жеребьёвках
- Уведомления о новых турнирах

**Команды бота:**

| Команда | Описание |
|---------|----------|
| `/reset_email email@example.com` | Привязать email к VK |
| `/reset_password` | Запросить код для восстановления пароля |
| `/tournaments` | Показать доступные турниры |
| `/subscribe_tournament [ID]` | Подписаться на турнир |
| `/unsubscribe_tournament [ID]` | Отписаться от турнира |
| `/help` | Показать список команд |

### 🔍 SEO-оптимизация
- Динамические мета-теги (title, description, keywords)
- Open Graph для соцсетей (VK, Facebook, Telegram)
- Twitter Cards
- Canonical URL
- Sitemap.xml (автогенерация)
- robots.txt
- Поддержка Яндекс Метрики и Google Analytics
---

## 🛠 Технологии

| Компонент | Технология |
|-----------|------------|
| Бэкенд | Laravel 13 (PHP 8.2) |
| База данных | MySQL 8.0 |
| WebSocket | Laravel Reverb / кастомная команда |
| Фронтенд | Blade, Bootstrap 5, jQuery |
| Шахматы | chess.js, chessboard.js |
| VK-бот | VK API (Long Polling) |
| SEO | Meta-теги, Open Graph, Sitemap |

---

## 📦 Установка

```bash
# Клонировать репозиторий
git clone https://github.com/ваш_логин/chessevent-laravel.git
cd chessevent

# Установить зависимости
composer install
npm install

# Настроить .env
cp .env.example .env
php artisan key:generate

# Настроить базу данных в .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chess_event_laravel
DB_USERNAME=root
DB_PASSWORD=

# Выполнить миграции
php artisan migrate

# Настроить VK-бота в .env
VK_GROUP_TOKEN=ваш_токен
VK_GROUP_ID=id_группы
VK_API_VERSION=5.199

# Запустить WebSocket
php artisan websocket:serve

# Запустить Vite
npm run dev

# Запустить Laravel
php artisan serve

# Запустить VK-бота (Polling)
php artisan vk:poll
