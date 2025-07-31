<?php

namespace Metaseller\TeletypeApp\services;

use Metaseller\TeletypeApp\ClientConnection;
use Metaseller\TeletypeApp\exceptions\TeletypeBadRequestBaseException;
use Metaseller\TeletypeApp\exceptions\TeletypeBadRequestExceptionWithContext;
use Metaseller\TeletypeApp\exceptions\TeletypeForbiddenException;
use Metaseller\TeletypeApp\exceptions\TeletypeLibraryException;
use Metaseller\TeletypeApp\TeletypeServices;
use Throwable;

/**
 * Абстрактный класс сервиса Teletype App Api
 *
 * @package Metaseller\TeletypeApp
 */
abstract class TeletypeBaseService
{
    /**
     * @var string[] Массив допустимых типов API запросов
     */
    protected const ALLOWED_REQUEST_TYPES = [
        'POST',
        'GET',
    ];

    /**
     * @var int Максимальная длина ответа, фиксируемая в логе
     */
    protected const MAX_CONTENT_LENGTH_IN_EXCEPTION = 2048;

    /**
     * @var TeletypeServices Фабрика доступа к сервисам Teletype App API
     */
    protected $_teletype_services_factory;

    /**
     * Конструктор класса
     *
     * @param TeletypeServices $teletype_services_factory Фабрика доступа к сервисам Teletype App API
     */
    public function __construct(TeletypeServices $teletype_services_factory)
    {
        $this->_teletype_services_factory = $teletype_services_factory;
    }

    /**
     * Метод создания экземпляра сервиса
     *
     * @param TeletypeServices $teletype_services_factory Фабрика доступа к сервисам Teletype App API
     *
     * @return static Текущий экземпляр Фабрики клиентов
     */
    public static function create(TeletypeServices $teletype_services_factory): self
    {
        return new static($teletype_services_factory);
    }

    /**
     * Метод для осуществления запросов к API Teletype App
     *
     * @param string $method Вызываемый метод API
     * @param array $data Данные запроса. По умолчанию равно <code>[]</code>
     * @param string $type Метод запроса. По умолчанию равно <code>POST</code>
     * @param bool $response_decode Признак необходимости декодировать ответ функцией json_decode. По умолчанию равно <code>true</code>
     * @param bool $raise Признак необходимости генерации исключения в случае возникновения ошибки. По умолчанию равно <code>true</code>
     *
     * @return array|string|null Данные из ответа API
     *
     * @throws TeletypeBadRequestBaseException
     * @throws TeletypeBadRequestExceptionWithContext
     * @throws TeletypeForbiddenException
     */
    public function request(string $method, array $data = [], string $type = 'POST', bool $response_decode = true, bool $raise = true)
    {
        $type = mb_strtoupper(trim($type));

        if (!in_array($type, static::ALLOWED_REQUEST_TYPES, true)) {
            throw new TeletypeBadRequestBaseException('Unsupported request type ' . $type);
        }

        $client_connection = $this->_teletype_services_factory->getClientConnection();

        if (!$api_token = $client_connection->getApiToken()) {
            throw new TeletypeForbiddenException('Please setup API token properly');
        }

        if (!$base_url = $client_connection->getApiBaseUrl()) {
            throw new TeletypeForbiddenException('Please setup API base url properly');
        }

        $request_url_params = ($type === 'GET') ? $data : [];
        $request_url_params['token'] = $api_token;

        $request_url = $base_url . '/' . $method . '?' . http_build_query($request_url_params);

        $request_header = [];

        if ($app_name = $client_connection->getAppName()) {
            $request_header[] = 'X-App-Name: ' . $app_name;
        }

        if ($type === 'POST') {
            $request_header[] = 'Content-Type: multipart/form-data';
        }

        $request_exception_base_error_message = 'Error on calling function "' . $method . '"';
        $request_exception = new TeletypeBadRequestExceptionWithContext($request_exception_base_error_message);
        $request_exception->setRequestPayload(array_merge($data, $request_url_params));

        try {
            $request_time = microtime(true);

            $ch = curl_init();

            curl_setopt_array($ch, $client_connection->getClientOptions());
            curl_setopt($ch, CURLOPT_URL, $request_url);

            if ($type === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }

            if ($request_header) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $request_header);
            }

            $response = curl_exec($ch);

            $request_time = microtime(true) - $request_time;
            $request_exception->setResponseTime($request_time);

            $response_http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $request_exception->setResponseHttpStatusCode($response_http_code);

            $response_content = (string) $response;
            $response_content_to_log = mb_strlen($response_content) <= static::MAX_CONTENT_LENGTH_IN_EXCEPTION ? $response_content : mb_substr($response_content, 0, static::MAX_CONTENT_LENGTH_IN_EXCEPTION) . '...';
            $request_exception->setResponseContent($response_content_to_log);

            if ($response_http_code !== 200) {
                $request_exception->setErrorText('Bad instance http response status');

                throw $request_exception;
            } elseif ($response_decode) {
                try {
                    $response_content = json_decode($response_content, true);
                } catch (Throwable $e) {
                    $request_exception->setErrorText('Bad instance response body. Impossible to json_decode');

                    throw $request_exception;
                }

                $success = (bool) $response_content['success'];

                if ($success === false) {
                    $request_exception->setErrorText($response_content['error']['message'] ?? 'Teletype api call error');
                    $request_exception->setErrorCode($response_content['error']['code'] ?? null);
                    $request_exception->setErrorMessage($response_content['error']['message'] ?? null);

                    throw $request_exception;
                } else {
                    $response_content = $response_content['data'] ?? [];
                }
            }

            return $response_content;
        } catch (Throwable $e) {
            if ($raise) {
                $request_exception->setErrorText('Error connecting with Teletype App API');

                throw $request_exception;
            }

            return null;
        }
    }

    /**
     * Метод получения текущей фабрики доступа к сервисам Teletype App API
     *
     * @return TeletypeServices Фабрика доступа к сервисам Teletype App API
     *
     * @throws TeletypeLibraryException
     */
    public function getTeletypeServices(): TeletypeServices
    {
        if (!$this->_teletype_services_factory) {
            throw new TeletypeLibraryException('Teletype service is not configured');
        }

        return $this->_teletype_services_factory;
    }
}
