<?php

namespace Metaseller\TeletypeApp\models;

use Metaseller\TeletypeApp\exceptions\TeletypeBadRequestBaseException;
use Metaseller\TeletypeApp\exceptions\TeletypeBadRequestExceptionWithContext;
use Metaseller\TeletypeApp\exceptions\TeletypeForbiddenException;
use Metaseller\TeletypeApp\exceptions\TeletypeLibraryException;

/**
 * Модель данных проекта Teletype App
 *
 * @package Metaseller\TeletypeApp
 *
 * @property string $id Идентификатор проекта
 * @property string $name Имя проекта
 * @property string $domain Домен проекта
 * @property string $ownerId Идентификатор владельца проекта
 * @property-read TeletypeOperator|null $owner Экземпляр модели владельца проекта
 * @property string $url URL проекта
 * @property array $createdAt Данные о дате/времени создания проекта
 */
class TeletypeProject extends TeletypeModel
{
    /**
     * @inheritDoc
     */
    protected const API_ATTRIBUTES_MAPPING = [
        'id' => 'id',
        'name' => 'name',
        'domain' => 'domain',
        'ownerId' => 'owner_id',
        'url' => 'url',
        'createdAt' => 'createdAt',
    ];

    /**
     * Метод получения экземпляра владельца проекта
     *
     * @return TeletypeOperator|null Экземпляр владельца проекта
     *
     * @throws TeletypeBadRequestBaseException
     * @throws TeletypeBadRequestExceptionWithContext
     * @throws TeletypeForbiddenException
     * @throws TeletypeLibraryException
     */
    public function getOwner(): ?TeletypeOperator
    {
        if (!$this->ownerId) {
            return null;
        }

        $available_operators = $this->teletypeServicesFactory->projectService->getOperators();

        foreach ($available_operators as $operator) {
            if ($operator->id === $this->ownerId) {
                return $operator;
            }
        }

        return null;
    }
}
