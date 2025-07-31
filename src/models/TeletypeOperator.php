<?php

namespace Metaseller\TeletypeApp\models;

/**
 * Модель данных оператора Teletype App
 *
 * @package Metaseller\TeletypeApp
 *
 * @property string $id Идентификатор оператора
 * @property string $email Email оператора
 * @property int $status Статус оператора
 * @property string $name Имя оператора
 * @property string $lastName Фамилия оператора
 * @property string $timezone Временная зона оператора
 * @property string $language Язык оператора
 * @property string[] $roles Массив ролей оператора
 */
class TeletypeOperator extends TeletypeModel
{
    /**
     * @var string Имя роли "Владелец проекта"
     */
    public const ROLE_OWNER = 'owner';

    /**
     * @var string Имя роли "Оператор"
     */
    public const ROLE_OPERATOR = 'operator';

    /**
     * @var string Имя роли "Администратор системы / Заместитель владельца проекта"
     */
    public const ROLE_ADMIN = 'admin';

    /**
     * @var string Имя роли "Помощник владельца проекта"
     */
    public const ROLE_HELPER = 'helper';

    /**
     * @var int Статус оператора "Ожидает подтверждения"
     */
    public const STATUS_WAIT_TO_CONFIRM = 10;

    /**
     * @var int Статус оператора "В процессе заполнения профиля"
     */
    public const STATUS_SETUP = 15;

    /**
     * @var int Статус оператора "Доступен"
     */
    public const STATUS_AVAILABLE = 20;

    /**
     * @var int Статус оператора "Занят"
     */
    public const STATUS_BUSY = 30;

    /**
     * @var int Статус оператора "Скрыт"
     */
    public const STATUS_HIDDEN = 40;

    /**
     * @var int Статус оператора "Удален из проекта"
     */
    public const STATUS_LOCKED = 50;

    /**
     * @inheritDoc
     */
    protected const API_ATTRIBUTES_MAPPING = [
        'id' => 'id',
        'email' => 'email',
        'status' => 'status',
        'name' => 'name',
        'lastName' => 'last_name',
        'timezone' => 'timezone',
        'language' => 'language',
        'roles' => 'roles',
    ];
}
