<?php

namespace Metaseller\TeletypeApp\models;

use Metaseller\TeletypeApp\exceptions\TeletypeLibraryException;
use Metaseller\TeletypeApp\TeletypeServices;

/**
 * Абстрактный класс модели данных Teletype App API
 *
 * @property-read TeletypeServices $teletypeServicesFactory Фабрика доступа к сервисам Teletype App API
 *
 * @package Metaseller\TeletypeApp
 */
abstract class TeletypeModel
{
    /**
     * @var array Массив маппинга имен атрибутов класса и полей ответа API запроса к Teletype App
     */
    protected const API_ATTRIBUTES_MAPPING = [];

    /**
     * @var TeletypeServices Родительская фабрика сервисов Teletype App
     */
    protected $_teletype_services_factory;

    /**
     * @var array Массив атрибутов, которые сохранены из ответа API запроса к Teletype App
     */
    protected $_api_response_attributes = [];

    /**
     * Конструктор класса
     *
     * @param TeletypeServices $teletype_services_factory Фабрика сервисов Teletype App
     */
    public function __construct(TeletypeServices $teletype_services_factory)
    {
        $this->_teletype_services_factory = $teletype_services_factory;
    }

    /**
     * Переопределение магического метода-геттера
     *
     * @throws TeletypeLibraryException
     */
    public function __get($name)
    {
        $method_getter = 'get' . ucfirst($name);

        if (method_exists($this, $method_getter)) {
            return $this->$method_getter();
        }

        if (array_key_exists($name, static::API_ATTRIBUTES_MAPPING)) {
            if ($api_attribute_name = static::API_ATTRIBUTES_MAPPING[$name]) {
                return $this->_api_response_attributes[$api_attribute_name] ?? null;
            }
        }

        throw new TeletypeLibraryException('Undefined property ' . __CLASS__ . '::$' . $name);
    }

    /**
     * Переопределение магического метода-ceттера
     *
     * @throws TeletypeLibraryException
     */
    public function __set($name, $value)
    {
        $method_setter = 'set' . ucfirst($name);

        if (method_exists($this, $method_setter)) {
            return $this->$method_setter($value);
        }

        if (array_key_exists($name, static::API_ATTRIBUTES_MAPPING)) {
            if ($api_attribute_name = static::API_ATTRIBUTES_MAPPING[$name]) {
                $this->_api_response_attributes[$api_attribute_name] = $value;

                return $value;
            }
        }

        throw new TeletypeLibraryException('Undefined property ' . __CLASS__ . '::$' . $name);
    }

    /**
     * Представление текущей модели данных в виде массива
     *
     * @return array Ассоциативный массив с атрибутами текущей модели
     */
    public function asArray(): array
    {
        $data = [];

        foreach (static::API_ATTRIBUTES_MAPPING as $class_property_name => $api_attribute) {
            $data[$class_property_name] = $this->_api_response_attributes[$api_attribute] ?? null;
        }

        return $data;
    }

    /**
     * Представление текущей модели данных в виде строки json
     *
     * @return string Строка json, содержащая массив с атрибутами текущей модели
     */
    public function asJson(): string
    {
        return json_encode($this->asArray());
    }

    /**
     * Метод обрабатывает ответ API запроса в виде массива и конвертирует его в объект текущего класса
     *
     * Метод использует настроенный маппинг параметров {@link static::API_ATTRIBUTES_MAPPING}
     *
     * @param TeletypeServices $teletype_services_factory Фабрика доступа к сервисам Teletype App API
     * @param array $data Ассоциативный массив данных
     *
     * @return static Созданный объект модели данных
     */
    public static function instantiateModel(TeletypeServices $teletype_services_factory, array $data): self
    {
        $model = new static($teletype_services_factory);

        foreach (static::API_ATTRIBUTES_MAPPING as $class_property_name => $api_attribute) {
            $model->{$class_property_name} = $data[$api_attribute] ?? null;
        }

        return $model;
    }

    /**
     * Метод обрабатывает ответ API запроса в виде списка массивов и конвертирует его в массив объект текущего класса
     *
     * Метод использует настроенный маппинг параметров {@link static::API_ATTRIBUTES_MAPPING}
     *
     * @param TeletypeServices $teletype_services_factory Фабрика доступа к сервисам Teletype App API
     * @param array $data Ассоциативный массив строк данных
     *
     * @return static[] Массив созданных объектов текущей модели данных
     */
    public static function instantiateArrayOfModels(TeletypeServices $teletype_services_factory, array $data): array
    {
        $models = [];

        foreach ($data as $row) {
            $models[] = static::instantiateModel($teletype_services_factory, $row);
        }

        return $models;
    }

    /**
     * Метод получения текущей фабрики доступа к сервисам Teletype App API
     *
     * @return TeletypeServices Фабрика доступа к сервисам Teletype App API
     *
     * @throws TeletypeLibraryException
     */
    public function getTeletypeServicesFactory(): TeletypeServices
    {
        if (!$this->_teletype_services_factory) {
            throw new TeletypeLibraryException('Teletype service is not configured');
        }

        return $this->_teletype_services_factory;
    }
}
