<?php

namespace Metaseller\TeletypeApp;

use Metaseller\TeletypeApp\services\TeletypeProjectService;
use Metaseller\TeletypeApp\traits\ServiceTrait;

/**
 * Фабрика доступа к сервисам Teletype App API
 *
 * @package Metaseller\TeletypeApp
 *
 * @property-read TeletypeProjectService $projectService Сервис для работы с проектами
 */
class TeletypeServices
{
    use ServiceTrait;

    /**
     * @var ClientConnection Экземпляр модели настроек соединения с API сервиса Teletype App
     */
    protected $_client_connection;

    /**
     * @var TeletypeProjectService Сервис для работы с проектами
     */
    protected $_project_service;

    /**
     * Конструктор класса
     *
     * @param string|null $api_token Токен доступа к Teletype App API
     * @param string|null $app_name Значение AppName для запросов к Teletype App API. Если установлено <code>null</code>,
     * то будет использовано значение {@link TeletypeServices::DEFAULT_APP_NAME}
     * @param ClientConnection|null $user_client_connection Экземпляр модели настроек соединения с API сервиса Teletype App
     */
    public function __construct(?string $api_token = null, ?string $app_name = null, ?ClientConnection $user_client_connection = null)
    {
        $this->setClientConnection($user_client_connection ?? (new ClientConnection()));

        if ($api_token) {
            $this->updateApiToken($api_token)->updateAppName($app_name ?: ClientConnection::DEFAULT_APP_NAME);
        }
    }

    /**
     * Метод обновляет токен доступа к Teletype App API
     *
     * @param string $api_token Токен доступа к Teletype App API
     *
     * @return $this Текущий экземпляр модели
     */
    public function updateApiToken(string $api_token): self
    {
        $this->getClientConnection()->setApiToken($api_token);
        $this->resetServices();

        return $this;
    }

    /**
     * Метод обновляет передаваемое в заголовке значение X-App-Name
     *
     * @param string $app_name Новое значение X-App-Name
     *
     * @return $this Текущий экземпляр модели
     */
    public function updateAppName(string $app_name): self
    {
        $this->getClientConnection()->setAppName($app_name);
        $this->resetServices();

        return $this;
    }

    /**
     * Метод установки пользовательского экземпляра модели настроек соединения с API сервиса Teletype App
     *
     * @param ClientConnection $user_client_connection Экземпляр модели настроек соединения с API сервиса Teletype App
     *
     * @return $this Текущий экземпляр
     */
    public function setClientConnection(ClientConnection $user_client_connection): self
    {
        $this->_client_connection = $user_client_connection;
        $this->resetServices();

        return $this;
    }

    /**
     * Метод создания экземпляра Фабрики сервисов
     *
     * @param string|null $api_token Токен доступа к Teletype App API
     * @param string|null $app_name Значение AppName для запросов к Teletype App API. Если установлено <code>null</code>,
     * то будет использовано значение {@link TeletypeServices::DEFAULT_APP_NAME}
     * @param ClientConnection|null $user_client_connection Экземпляр модели настроек соединения с API сервиса Teletype App
     *
     * @return static Текущий экземпляр Фабрики клиентов
     */
    public static function create(string $api_token, ?string $app_name = null, ClientConnection $user_client_connection = null): self
    {
        return new static($api_token, $app_name, $user_client_connection);
    }

    /**
     * Метод сброса созданных моделей клиентов в рамках экземпляра фабрики
     */
    protected function resetServices(): void
    {
        $this->_project_service = null;
    }

    /**
     * Метод получения сервиса работы с проектами в режиме синглтона
     *
     * @return TeletypeProjectService Сервис работы с проектами
     */
    public function getProjectService(): TeletypeProjectService
    {
        if (!$this->_project_service) {
            $this->_project_service = TeletypeProjectService::create($this);
        }

        return $this->_project_service;
    }

    /**
     * Метод получения текущей модели настроек соединения с API Teletype App
     *
     * @return ClientConnection Модель настроек соединения с API Teletype App
     */
    public function getClientConnection(): ClientConnection
    {
        if (!$this->_client_connection) {
            $this->_client_connection = new ClientConnection();
        }

        return $this->_client_connection;
    }
}
