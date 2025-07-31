<?php

use Metaseller\TeletypeApp\exceptions\TeletypeBadRequestExceptionWithContext;
use Metaseller\TeletypeApp\TeletypeServices;
use Metaseller\TeletypeApp\models\TeletypeOperator;

require(__DIR__ . '/../vendor/autoload.php');

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

/**
 * А теперь получим список операторов в проекте
 *
 * Запрос не принимает никаких параметров на вход
 *
 * @see https://teletype.app/help/api/#tag/Project/paths/~1project~1operators/get
 */
try {
    $operators = $teletype_app->projectService->getOperators();
} catch (Throwable $e) {
    echo 'Api error:' . PHP_EOL;

    if ($e instanceof TeletypeBadRequestExceptionWithContext) {
        var_dump($e->getExceptionContext());
    } else {
        var_dump($e->getMessage());
    }

    die();
}

echo 'Project has ' . count($operators) . ' operators:' . PHP_EOL;

/** Выведем полученную информацию в виде списка массива */
foreach ($operators as $operator) {
    echo '- Operator with id "' . $operator->id . '" data:' . PHP_EOL . PHP_EOL;

    var_dump($operator->asArray());

    echo PHP_EOL;
}

echo PHP_EOL;

/** Также мы можем получить экземпляр владельца проекта как объект класса {@link TeletypeOperator} */

$owner = $project->owner;

if ($owner) {
    /** Выводим полученную информацию о проекте в виде массива */
    echo 'Project owner data:' . PHP_EOL;
    var_dump($owner->asArray());

    /** Выводим полученную информацию о проекте в сериализованном виде */
    var_dump($owner->asJson());
} else {
    echo 'Owner model is empty';
}
