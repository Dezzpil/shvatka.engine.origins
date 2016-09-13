# shvatka.engine.origins
Сокращенно sh.e (в настройках конфигурации полное название писать слишком неудобно)

## Используем Vagrant
Поднимаем полностью настроенную виртуальную машину одной строчкой:

```bash
vagrant up --provider virtualbox
```

Установка LEMP сохранена в bootstrap.sh 

## Пробуем запустить ipb2.1.7 сегодня
Как поставить древний ipb2.1.7 на PHP5.5 и MySQL 5.6?

* Перекодировал все возможные файлы ipb2.1.7 из cp1251 в utf-8, используя iconv:
```bash
find . | while read i; do 
    cp "$i" "$i".2iconv;
    iconv -c -f cp1251 -t utf-8 "$i".2iconv >"$i".converted;
    mv "$i".converted "$i";
    rm "$i".2iconv;
done
```
* В /install/index.php в 33 строке позволит следить за состоянием дел:
```php
//error_reporting  (E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL);
//set_magic_quotes_runtime(0);
```

* Надо избавиться от ошибки с передачей значений по ссылке [вот так](http://stackoverflow.com/questions/8971261/php-5-4-call-time-pass-by-reference-easy-fix-available)
* Надо избавиться от ошибки в запросах создания таблиц (MySQL изменил синтаксис между мажорными версиями) [вот так](http://stackoverflow.com/questions/12428755/1064-error-in-create-table-type-myisam)