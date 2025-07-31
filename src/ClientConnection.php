<?php

namespace Metaseller\TeletypeApp;

/**
 * Модель получения настроек соединения с API сервиса Teletype App
 *
 * @package Metaseller\TeletypeApp
 */
class ClientConnection
{
    /**
     * @var string X-App-Name запросов по-умолчанию
     *
     * Передается в header запросов необязательным параметром
     */
    public const DEFAULT_APP_NAME = 'metaseller.teletype-api-php';

    /**
     * @var string Базовый URL для построения адреса эндпоинтов Tinkoff Invest API
     *
     * @see https://teletype.app/help/api/#section/Obshaya-informaciya
     */
    public const TELETYPE_APP_API_ENDPOINT = 'https://api.teletype.app/public/api/v1';

    /**
     * @var int Таймаут соединения по умолчанию в секундах
     *
     * CURLOPT_CONNECTTIMEOUT
     */
    public const TELETYPE_APP_API_DEFAULT_CONNECT_TIMEOUT = 5;

    /**
     * @var int Таймаут соединения по умолчанию в секундах
     *
     * CURLOPT_TIMEOUT
     */
    public const TELETYPE_APP_API_DEFAULT_TIMEOUT = 10;

    /**
     * @var string Базовый URL для построения адреса эндпоинтов Tinkoff Invest API
     *
     * По умолчанию равен {@link ClientConnection::TELETYPE_APP_API_ENDPOINT}
     */
    protected $_api_base_url = self::TELETYPE_APP_API_ENDPOINT;

    /**
     * @var string|null Токен доступа к API Teletype App
     */
    protected $_api_token = null;

    /**
     * @var string|null Передаваемое AppName запросов
     */
    protected $_app_name = self::DEFAULT_APP_NAME;

    /**
     * @var array Массив опций для Curl Transfer
     *
     * @see https://www.php.net/manual/en/function.curl-setopt.php
     */
    protected $_curl_options = [];

    /**
     * Конструктор класса
     */
    public function __construct()
    {
        $this->correctionDefaultClientOptions();
    }

    /**
     * Метод-сеттер параметра {@link ClientConnection::$_api_base_url}
     *
     * @param string $base_url Базовый URL для построения адреса эндпоинтов Tinkoff Invest API
     *
     * @return $this Текущий экземпляр
     */
    public function setApiBaseUrl(string $base_url): self
    {
        $this->_api_base_url = $base_url;

        return $this;
    }

    /**
     * Метод-геттер параметра {@link ClientConnection::$_api_base_url}
     *
     * @return string Базовый URL для построения адреса эндпоинтов Tinkoff Invest API
     */
    public function getApiBaseUrl(): string
    {
        return (string) $this->_api_base_url;
    }

    /**
     * Метод-сеттер параметра {@link ClientConnection::$_api_token}
     *
     * Токен доступа к API Teletype App вы можете получить в настройках своего личного кабинета {@link https://panel.teletype.app}
     *
     * @param string $api_token Токен доступа к API Teletype App
     *
     * @return $this Текущий экземпляр
     */
    public function setApiToken(string $api_token): self
    {
        $this->_api_token = $api_token;

        return $this;
    }

    /**
     * Метод-геттер параметра {@link ClientConnection::$_api_token}
     *
     * @return string Токен доступа к API Teletype App
     */
    public function getApiToken(): string
    {
        return (string) $this->_api_token;
    }

    /**
     * Установка значений опций соединения с сервисом Teletype App Api по умолчанию
     *
     * @return $this Текущий экземпляр
     */
    public function correctionDefaultClientOptions(): self
    {
        $this->_curl_options[CURLOPT_CONNECTTIMEOUT] = $this->_curl_options[CURLOPT_CONNECTTIMEOUT] ?? static::TELETYPE_APP_API_DEFAULT_CONNECT_TIMEOUT;
        $this->_curl_options[CURLOPT_TIMEOUT] = $this->_curl_options[CURLOPT_TIMEOUT] ?? static::TELETYPE_APP_API_DEFAULT_TIMEOUT;
        $this->_curl_options[CURLOPT_SSL_VERIFYPEER] = $this->_curl_options[CURLOPT_SSL_VERIFYPEER] ?? 0;
        $this->_curl_options[CURLOPT_SSL_VERIFYHOST] = $this->_curl_options[CURLOPT_SSL_VERIFYHOST] ?? 0;
        $this->_curl_options[CURLOPT_VERBOSE] = $this->_curl_options[CURLOPT_VERBOSE] ?? 0;

        $this->_curl_options[CURLOPT_RETURNTRANSFER] = 1;

        unset($this->_curl_options[CURLOPT_URL]);
        unset($this->_curl_options[CURLOPT_CUSTOMREQUEST]);

        return $this;
    }

    /**
     * Метод установки пользовательских опций соединения с сервисом Teletype App Api
     *
     * @param array $user_options Массив пользовательских настроек для Curl Transfer
     *
     * @return $this Текущий экземпляр
     */
    public function setClientOptions(array $user_options = []): self
    {
        $this->_curl_options = $user_options;
        $this->correctionDefaultClientOptions();

        return $this;
    }

    /**
     * Метод частичного обновления опций соединения с сервисом Teletype App Api
     *
     * @param array $user_options Массив пользовательских настроек для Curl Transfer
     *
     * @return $this Текущий экземпляр
     */
    public function updateClientOptions(array $user_options = []): self
    {
        $this->setClientOptions(array_merge($this->_curl_options, $user_options));

        return $this;
    }

    /**
     * Метод получения опций соединения с сервисом Teletype App Api
     *
     * @return array Массив опций соединения с сервисом Teletype App Api
     */
    public function getClientOptions(): array
    {
        return $this->_curl_options;
    }

    /**
     * Метод-сеттер значения {@link ClientConnection::$_app_name}
     *
     * @param string $app_name X-App-Name для запросов к Teletype App Api
     *
     * @return $this Текущий экземпляр модели
     */
    public function setAppName(string $app_name): self
    {
        $this->_app_name = $app_name;

        return $this;
    }

    /**
     * Метод-геттер значения {@link ClientConnection::$_app_name}
     *
     * @return string X-App-Name для запросов к Teletype App Api
     */
    public function getAppName(): string
    {
        return (string) $this->_app_name;
    }
}
