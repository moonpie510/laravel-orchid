## О проекте
Это админ панель для работы с клиентами, которая сделана с помощью Orchid

### Как установить
#### 1) Скачиваем репозиторий
```shell
git clone git@github.com:moonpie510/laravel-orchid.git
    
или
    
git clone https://github.com/moonpie510/laravel-orchid.git
```

#### 2) Установка зависимостей
```shell
composer install
```

#### 3) Скопировать файл .env и прописать в него свои данные для подключения к базе данных
```shell
cp .env.example .env
```

#### 4) Генерация ключа (если надо)
```shell
php artisan key:generate
```

#### 5) Миграция с seed
```shell
php atrisan migrate --seed
```

#### 6) Запуск сервера
```shell
php artisan serve
```
