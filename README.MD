# Что это?
Проект для реализации тестовых задач.
Сейчас в проекте:
- [Бинарное дерево](#Бинарное-дерево)

## Бинарное дерево
Что сделано:
- [X] Добавление узлов
- [X] Балансировка дерева при добавлении
- [X] Отображение дерева в виде массива с тремя элементами (корень-левое поддерево-правое поддерево)
- [X] Отображение дерева в виде массива по уровням (в первом элементе — корень, во втором — дочки, в третьем — внучки и т.д.)
- [X] Поиск узла по ключу
- [X] Удаление узла по ключу
- [X] Классы репозитория "App\Services\Binary" можно наследовать и использовать с разными данными. UserBinaryTree пример приспособления сущности для того, чтобы быть реализованным в виде дерева.

Что можно сделать:
- [ ] Балансировка при удалении
- [ ] Усовершенствовать вывод в виде массива по уровням. Сейчас в некоторых состояниях дерева не понятны связи.

# Как начать?
Разверните проект, установите пакеты композером.
Перед началом создайте БД и таблицы, загрузите фикстуры:
```bash
php bin/console doctrine:database:create 
php bin/console doctrine:schema:create 
php bin/console doctrine:migrations:migrate 
php bin/console doctrine:fixtures:load
```

# Заметки

## Докер состоит из:
- PHP - 8 (+ composer)
- NGINX - 1.16.0-alpine
- Postgres
- Adminer

## Команда запуска
```bash
docker-compose up -d
```

## Прочие команды 

Просмотр логов:
```bash
docker logs Имя_контейнера
```
Зайти из-под контейнера:
```bash
docker-compose exec engine bash
```

## Make-файл

Make-файл - это краткий вызов одной или нескольких команд, прописанных в Makefile. 


# Создание проекта

##Установка и запуск из-под докера
Установка симфони из-под докера:
```bash
docker-compose exec engine bash
composer create-project symfony/skeleton my_project_directory
```
Проверить права доступов к файлам, если нет, то выдать(из-под докера можно):
```bash
ls -l
chown -R www-data my_project_directory/
```
Переместить файлы в основную директорию:
```bash
sudo mv -i my_project_directory/* .
```
Объединение переменных окружения и игнорируемых гитом файлов:
```bash
cat my_project_directory/.env >> .env.example
cat my_project_directory/.env >> .env
cat my_project_directory/.gitignore >> .gitignore
```
Удаляем, директория my_project_directory больше не нужна
```bash
rm -R my_project_directory/
```
Изменим путь корневой у nginx в файле - docker/nginx/default.conf на:
```bash
root /var/www/html/public;
```
Перезапустить контейнер:
```bash
docker-compose restart nginx
```

Симфони готов к работе.

##Установка бандлов

```bash
composer require symfony/flex
composer require symfony/maker-bundle --dev
composer require symfony/security-bundle
composer require doctrine/doctrine-bundle
composer require doctrine/doctrine-migrations-bundle
```

Самый простой способ создать пользовательский класс — использовать команду make:user из MakerBundle:
```bash
php bin/console doctrine:database:create
php bin/console make:user
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

```bash
composer require zenstruck/foundry --dev
composer require --dev orm-fixtures
```