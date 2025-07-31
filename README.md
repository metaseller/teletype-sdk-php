# teletype-sdk-php
PHP SDK for Teletype App API

https://teletype.app/ является популярным удобным агрегатором чатов и сообщений из мессенджеров и социальных сетей для бизнеса.

ВАЖНО: Данная библиотека является пока еще НЕ ЯВЛЯЕТСЯ полноценным SDK для работы с Teletype App API через PHP. 
Она находится в ранней стадии разработки. В данный момент в библиотеке пробно реализованы вызовы двух методов. 
В настоящее время ведется работа по добавлению всех доступных методов API 

Документация по API Teletype App в формате Swagger 3.0 для разработчиков доступна по ссылке: https://teletype.app/help/api/

По всем вопросам работы с API обращайтесь к нам по email: [p@teletype.app](mailto:p@teletype.app)

# Структура текущего репозитория:

```
examples - Директория с примерами подключения к сервису и выполнением простейших запросов
src - Основная кодовая база SDK
src/exceptions - Модели исключений, используемые в SDK
src/models - Модели данных, в которые маппятся ответы API методов
src/services - API сервис Teletype App семантически разделен на набор сервисов, обслуживающих те или иные разделы API
src/traits - Вспомогательные трейты 
```

# Требования для установки

Для начала работы нам потребуется:
* PHP 7.3 или новее (я делал и тестировал на php 7.4 / Ubuntu 18.04.5)
* PECL, Composer

# Устанавливаем через composer

Вы можете скачать данный SDK с GitHub по ссылке https://github.com/metaseller/teletype-sdk-php, либо просто выполнить в 
консоли команды

```
$ git clone git@github.com:metaseller/teletype-sdk-php .
composer update
```

Также вы можете установить SDK в свой проект через [composer](http://getcomposer.org/download/)

```
$ composer require metaseller/teletype-sdk-php 
```

Для работы с API вам понадобится token доступа. Для этого Вам нужно зарегистрироваться в сервисе https://teletype.app/, 
создать свой проект, перейти в настройки проекта (https://panel.teletype.app/settings/public-api), активировать доступ 
к Teletype App Public Api и скопировать токен. 

Прописывайте свой Teletype App API token и запускайте пример. 
```
$ vim examples/example1.php
```

```php
/**
 * Ваш токен доступа к API
 *
 * @see https://panel.teletype.app/settings/public-api
 */
$token = 'FQWF......................................e53gA';
```

и тестируем:
```
$ php examples/example1.php
```

# Тестовые примеры

Удобно использовать фабрику создания сервисов доступа к сервисам Teletype App API

```php
/**
 * Ваш токен доступа к API
 *
 * @see https://panel.teletype.app/settings/public-api
 */
$token = '<Your Teletype App Public Api Token>';

/** Инициализируем фабрику сервисов */

$teletype_app = TeletypeServices::create($token);

/**
 * Выполним запроса к API на получение информации о проекте
 *
 * Запрос не принимает никаких параметров на вход
 *
 * @see https://teletype.app/help/api/#tag/Project/paths/~1project~1details/get
 */
try {
    $project = $teletype_app->projectService->getProject();
} catch (Throwable $e) {
    echo 'Api error:' . PHP_EOL;

    if ($e instanceof TeletypeBadRequestExceptionWithContext) {
        var_dump($e->getExceptionContext());
    } else {
        var_dump($e->getMessage());
    }

    die();
}

/** Выводим полученную информацию о проекте в виде массива */
echo ' - Project "' . $project->id . "' data:" . PHP_EOL . PHP_EOL;
echo 'Project as array:' . PHP_EOL;

var_dump($project->asArray());

echo PHP_EOL;

/** Выводим полученную информацию о проекте в сериализованном виде */
echo PHP_EOL . 'Project as json:' . PHP_EOL;

var_dump($project->asJson());

echo PHP_EOL;

```

Также есть возможность с автоматическим выполнением подзапросов получать расширенную информацию в рамках реализованных моделей данных

```php
/** Мы можем получить экземпляр владельца проекта как объект класса {@link TeletypeOperator} */

try {
    $owner = $project->owner;
} catch (Throwable $e) {
    // Поскольку при получении экземпляра владельца делается подзапрос, то возможно возникновение исключений, которые нужно 
    // корректно обработать в рамках вашего кода
}

if ($owner) {
    /** Выводим полученную информацию о проекте в виде массива */
    echo 'Project owner data:' . PHP_EOL;
    var_dump($owner->asArray());

    /** Выводим полученную информацию о проекте в сериализованном вида */
    var_dump($owner->asJson());
} else {
    echo 'Owner model is empty';
}
```
