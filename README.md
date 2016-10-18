# shvatka.engine.origins

## Установка
Для работы с кодом используется Vagrant - это по, которое позволяет автоматизировать конфигурирование сервера. 
Оно полагается на провайдеры виртуальных машин, например, VirtualBox и полностью берет на себя процесс ее запуска и настройки. Все, что нам надо - один раз описать какую ОС и какие библиотеки надо скачать и как их настроить.
Благодаря этому у каждого разработчика идентичное окружение, а все изменения в нем сохраняются в репозитории.
У Vagrant очень удобный гетстартед. Ознакомление не займет много времени

* Скачиваем и устанавливаем Vagrant - https://www.vagrantup.com/downloads.html
* Клонируем репозиторий
* Копируем config.sample.json в config.json. В нем уже прописаны данные для доступа к СУБД виртуальной машины

## Запуск
Запускаем терминал и поднимаем виртуальную машину:
```bash
vagrant up --provider virtualbox
```

Vagrant автоматически пробрасывает порты и поэтому нам ничего не остается кроме как обратиться к нашему сайту: 127.0.0.1:4567

## Пользователи
Список тестовых пользователей можно посмотреть в дампе БД - db/shvatka.sql
Пароль для всех тестовых пользователей - Normandia

## Тестирование
Тесты надо запускать с локальной машины. 
Впрочем, если есть большое желание запускать тесты на самой виртуальной машине, то нужно поправить порт БД в config.json секции testing с 3307, на 3306.
```bash
cd \vagrant
phpunit
```

PHPUnit ставится на виртуальную машину автоматически.

# Пробуем запустить ipb2.1.7 сегодня
Как поставить древний ipb2.1.7 на PHP5.5 и MySQL 5.6?

* Перекодировать все возможные файлы ipb2.1.7 из cp1251 в utf-8, используя iconv:
* В /install/index.php в 33 строке позволит следить за состоянием дел:
```php
//error_reporting  (E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL);
//set_magic_quotes_runtime(0);
```

* Надо избавиться от ошибки с передачей значений по ссылке [вот так](http://stackoverflow.com/questions/8971261/php-5-4-call-time-pass-by-reference-easy-fix-available)
* Надо избавиться от ошибки в запросах создания таблиц (MySQL изменил синтаксис между мажорными версиями) [вот так](http://stackoverflow.com/questions/12428755/1064-error-in-create-table-type-myisam)
