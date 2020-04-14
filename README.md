[![codecov](https://codecov.io/gh/wyster/symfony-based-inn-checker/branch/master/graph/badge.svg)](https://codecov.io/gh/wyster/symfony-based-inn-checker)

# Запуск проекта

Проект можно запустить через docker-compose, для этого нужно

* Перейти в директорию `.docker` : `cd .docker`

* Запустить команду `make setup`

* Отредактировать `.env`, а именно порты по которым будет доступен веб сервер и база данных

```
HTTP_PORT=80
MYSQL_PORT=3306
````

* Запустить контейнеры

`make up`

Подьём может занят время, устанавливаются пакеты composer, выполняються миграции, отследить процесс можно через `make logs`

* После того как все контейнеры запущены проверить работу обратившись через браузер по `http://localhost:$HTTP_PORT`

* К базе можно подключиться по `localhost:$DB_PORT`, login: `dev`, password: `dev`

Другие доступные команды можно посмотреть выполнив `make`
