<?php

namespace Metaseller\TeletypeApp\exceptions;

use Exception;
use Throwable;

/**
 * Класс API-исключения (возникшего как правило вследствие ошибки выполнения запроса или передачи некорректных параметров на вход),
 * содержащего дополнительную информацию о контексте исполнения
 *
 * @package Metaseller\TeletypeApp
 */
class TeletypeBadRequestExceptionWithContext extends TeletypeBadRequestBaseException
{
    /**
     * @var string|null Полный URL HTTP запроса
     */
    protected $_request_url;

    /**
     * @var mixed|null Массив полезной нагрузки HTTP запроса. Параметр должен допускать сериализацию в JSON
     */
    protected $_request_payload;

    /**
     * @var string|null Текст ошибки запроса к API
     */
    protected $_error_text;

    /**
     * @var string|null Код ошибки запроса к API
     */
    protected $_error_code;

    /**
     * @var string|null Системное сообщение об ошибки запроса к API
     */
    protected $_error_message;

    /**
     * @var string|null Тип ошибки запроса к API
     */
    protected $_error_type;

    /**
     * @var float|null Время выполнения запроса в секундах
     */
    protected $_response_time;

    /**
     * @var int|null Код ответа HTTP запроса к API
     */
    protected $_response_http_status_code;

    /**
     * @var string|null Контент ответа HTTP запроса к API
     */
    protected $_response_content;

    /**
     * @var string|null Идентификатор API запроса
     */
    protected $_request_id;

    /**
     * @var string|null Версия API
     */
    protected $_api_version;

    /**
     * Конструктор класса
     *
     * @param string|null $message Текст, описывающий ошибку/событие. По умолчанию равно <code>null</code>
     * @param int|null $code Код статуса ответа HTTP запроса. По умолчанию равно <code>400</code>
     * @param Exception|null $previous Предыдущее исключение в цепочке исключений. По умолчанию равно <code>null</code>
     * @param array $exception_context Дополнительная информация об исключении.
     * Формат {@link TeletypeBadRequestExceptionWithContext::additionExceptionContext()}
     */
    public function __construct($message = null, $code = 400, Exception $previous = null, array $exception_context = [])
    {
        parent::__construct($message, $code, $previous);

        static::additionExceptionContext($this, $exception_context);
    }

    /**
     * Метод-геттер полного URL HTTP запроса
     *
     * @return string|null Полный URL HTTP запроса, если указан
     */
    public function getRequestUrl(): ?string
    {
        return $this->_request_url;
    }

    /**
     * Метод-сеттер эндпоинта HTTP запроса
     *
     * @param string|null $request_url Полный URL HTTP запроса. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setRequestUrl(?string $request_url): self
    {
        $this->_request_url = $request_url;

        return $this;
    }

    /**
     * Метод-геттер массива полезной нагрузки HTTP запроса
     *
     * @return mixed|null Массив полезной нагрузки HTTP запроса, если указан
     */
    public function getRequestPayload()
    {
        return $this->_request_payload;
    }

    /**
     * Метод-сеттер массива полезной нагрузки HTTP запроса
     *
     * @param string|array|null $request_payload Массив полезной нагрузки HTTP запроса. Параметр должен допускать сериализацию в JSON. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setRequestPayload($request_payload): self
    {
        $this->_request_payload = $request_payload;

        return $this;
    }

    /**
     * Метод-геттер кода ошибки запроса к API
     *
     * @return string|null Код ошибки запроса к API, если указан
     */
    public function getErrorCode(): ?string
    {
        return $this->_error_code;
    }

    /**
     * Метод-сеттер кода ошибки запроса к API
     *
     * @param string|null $error_code Код ошибки запроса к API. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setErrorCode(?string $error_code): self
    {
        $this->_error_code = $error_code;

        return $this;
    }

    /**
     * Метод-геттер текста ошибки запроса к API
     *
     * @return string|null Текст ошибки запроса к API, если указан
     */
    public function getErrorText(): ?string
    {
        return $this->_error_text;
    }

    /**
     * Метод-сеттер текста ошибки запроса к API
     *
     * @param string|null $error_text Текст ошибки запроса к API. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setErrorText(?string $error_text): self
    {
        $this->_error_text = $error_text;

        return $this;
    }

    /**
     * Метод-геттер внутреннего сообщения об ошибке запроса к API
     *
     * @return string|null Текст внутреннего сообщения об ошибке запроса к API, если указан
     */
    public function getErrorMessage(): ?string
    {
        return $this->_error_message;
    }

    /**
     * Метод-сеттер внутреннего сообщения об ошибке запроса к API
     *
     * @param string|null $error_message Текст внутреннего сообщения об ошибке запроса к API. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setErrorMessage(?string $error_message): self
    {
        $this->_error_message = $error_message;

        return $this;
    }

    /**
     * Метод-геттер типа ошибки запроса к API
     *
     * @return string|null Тип ошибки запроса к API, если указан
     */
    public function getErrorType(): ?string
    {
        return $this->_error_type;
    }

    /**
     * Метод-сеттер версии API
     *
     * @param string|null $api_version Версия API запроса. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setApiVersion(?string $api_version): self
    {
        $this->_api_version = $api_version;

        return $this;
    }

    /**
     * Метод-геттер версии API
     *
     * @return string|null Тип ошибки запроса к API, если указан
     */
    public function getApiVersion(): ?string
    {
        return $this->_api_version;
    }

    /**
     * Метод-сеттер типа ошибки запроса к API
     *
     * @param string|null $error_type Тип ошибки запроса к API. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setErrorType(?string $error_type): self
    {
        $this->_error_type = $error_type;

        return $this;
    }

    /**
     * Метод-геттер времени выполнения запроса к API
     *
     * @return float|null Время выполнения запроса в секундах, если указан
     */
    public function getResponseTime(): ?float
    {
        return $this->_response_time;
    }

    /**
     * Метод-сеттер времени выполнения запроса к API
     *
     * @param float|null $request_time Время выполнения запроса в секундах. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setResponseTime(?float $request_time): self
    {
        $this->_response_time = $request_time;

        return $this;
    }

    /**
     * Метод-геттер контента ответа HTTP запроса к API
     *
     * @return string|null Контент ответа HTTP запроса к API, если указан
     */
    public function getResponseContent(): ?string
    {
        return $this->_response_content;
    }

    /**
     * Метод-сеттер контента ответа HTTP запроса к API
     *
     * @param string|array|null $response_content Контент ответа HTTP запроса к API. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setResponseContent($response_content): self
    {
        $this->_response_content = $response_content;

        return $this;
    }

    /**
     * Метод-геттер кода статуса HTTP запроса к API
     *
     * @return int|null Код статуса HTTP запроса к API, если указан
     */
    public function getResponseHttpStatusCode(): ?int
    {
        return $this->_response_http_status_code;
    }

    /**
     * Метод-сеттер кода статуса HTTP запроса к API
     *
     * @param int|null $response_http_status_code Код статуса HTTP запроса к API. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setResponseHttpStatusCode(?int $response_http_status_code): self
    {
        $this->_response_http_status_code = $response_http_status_code;

        return $this;
    }

    /**
     * Метод-геттер символьного идентификатора API запроса
     *
     * @return string|null Строковый идентификатор запроса.
     */
    public function getRequestId(): ?string
    {
        return $this->_request_id;
    }

    /**
     * Метод-сеттер символьного идентификатора API запроса
     *
     * @param string|null $request_id Строковый идентификатор запроса. По умолчанию равно <code>null</code>
     *
     * @return $this Текущий экземпляр исключения
     */
    public function setRequestId(?string $request_id): self
    {
        $this->_request_id = $request_id;

        return $this;
    }

    /**
     * Метод возвращает полный контекст ошибки в виде массива
     *
     * @return array Контекст ошибки в виде массива. Формат ответа см {@link TeletypeBadRequestExceptionWithContext::additionExceptionContext()}
     */
    public function getExceptionContext(): array
    {
        return array_filter([
            'api_version' => $this->getApiVersion(),

            'request_id' => $this->getRequestId(),
            'request_url' => $this->getRequestUrl(),
            'request_payload' => $this->getRequestPayload(),

            'response_time' => $this->getResponseTime(),
            'response_content' => $this->getResponseContent(),

            'response_http_status' => $this->getResponseHttpStatusCode(),

            'error_text' => $this->getErrorText(),
            'error_message' => $this->getErrorMessage(),
            'error_code' => $this->getErrorCode(),
            'error_type' => $this->getErrorType(),
        ]);
    }

    /**
     * Метод дополняет информацию об исключении данными переданного контекста
     *
     * Если метод получает на вход <code>null</code>, то он пропускает обработку
     *
     * @param Throwable|null $exception Исключение к обработке или <code>null</code>
     * @param array $exception_context Массив данных для исключения, допустимые параметры:
     * <pre>
     *  [
     *      'api_version'   => (string),
     *
     *      'request_id'        => (string),
     *      'request_url'       => (string),
     *      'request_payload'   => (array|string)
     *
     *      'response_time'         => (int),
     *      'response_content'      => (array|string),
     *
     *      'response_http_status'  => (int),
     *
     *      'error_text'    => (string),
     *      'error_message' => (string),
     *      'error_code'    => (string),
     *      'error_type'    => (string),
     *  ]
     * </pre>
     *
     * @return Throwable|null
     */
    public static function additionExceptionContext(Throwable $exception = null, array $exception_context = []): ?Throwable
    {
        if (!$exception_context) {
            return $exception;
        }

        if ($exception instanceof TeletypeBadRequestExceptionWithContext) {
            if ($api_version = ($exception_context['api_version'] ?? null)) {
                $exception->setApiVersion($api_version);
            }

            if ($request_id = ($exception_context['request_id'] ?? null)) {
                $exception->setRequestId($request_id);
            }

            if ($request_url = ($exception_context['request_url'] ?? null)) {
                $exception->setRequestUrl($request_url);
            }

            if ($request_payload = ($exception_context['request_payload'] ?? null)) {
                $exception->setRequestPayload($request_payload);
            }

            if ($response_time = ($exception_context['response_time'] ?? null)) {
                $exception->setResponseTime($response_time);
            }

            if ($response_content = ($exception_context['response_content'] ?? null)) {
                $exception->setResponseContent($response_content);
            }

            if ($response_http_status = ($exception_context['response_http_status'] ?? null)) {
                $exception->setResponseHttpStatusCode($response_http_status);
            }

            if ($error_code = ($exception_context['error_code'] ?? null)) {
                $exception->setErrorCode($error_code);
            }

            if ($error_text = ($exception_context['error_text'] ?? null)) {
                $exception->setErrorText($error_text);
            }

            if ($error_message = ($exception_context['error_message'] ?? null)) {
                $exception->setErrorMessage($error_message);
            }

            if ($error_type = ($exception_context['error_type'] ?? null)) {
                $exception->setErrorType($error_type);
            }
        }

        return $exception;
    }
}
