<?php

namespace Metaseller\TeletypeApp\services;

use Metaseller\TeletypeApp\exceptions\TeletypeBadRequestBaseException;
use Metaseller\TeletypeApp\exceptions\TeletypeBadRequestExceptionWithContext;
use Metaseller\TeletypeApp\exceptions\TeletypeForbiddenException;
use Metaseller\TeletypeApp\exceptions\TeletypeLibraryException;
use Metaseller\TeletypeApp\models\TeletypeOperator;
use Metaseller\TeletypeApp\models\TeletypeProject;

/**
 * Модель сервиса работы с проектами Teletype App API
 *
 * @package Metaseller\TeletypeApp
 */
class TeletypeProjectService extends TeletypeBaseService
{
    /**
     * @var TeletypeOperator[] Закешированное значение массива операторов проекта
     */
    protected $_operators = [];

    /**
     * Метод получения экземпляра проекта
     *
     * @return TeletypeProject Экземпляр проекта
     *
     * @throws TeletypeBadRequestBaseException
     * @throws TeletypeBadRequestExceptionWithContext
     * @throws TeletypeForbiddenException
     * @throws TeletypeLibraryException
     */
    public function getProject(): TeletypeProject
    {
        $project_data = $this->request('project/details', [], 'GET');

        return TeletypeProject::instantiateModel($this->getTeletypeServices(), $project_data);
    }

    /**
     * Метод получения операторов проекта
     *
     * @return TeletypeOperator[] Массив экземпляров операторов
     *
     * @throws TeletypeBadRequestBaseException
     * @throws TeletypeBadRequestExceptionWithContext
     * @throws TeletypeForbiddenException
     * @throws TeletypeLibraryException
     */
    public function getOperators(bool $refresh = false): array
    {
        if (!$refresh && $this->_operators) {
            return $this->_operators;
        }

        $operators_data = $this->request('project/operators', [], 'GET');

        $this->_operators = TeletypeOperator::instantiateArrayOfModels($this->getTeletypeServices(), $operators_data);

        return $this->_operators;
    }
}
