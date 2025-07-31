<?php

namespace Metaseller\TeletypeApp\traits;

use Metaseller\TeletypeApp\exceptions\TeletypeLibraryException;

/**
 * Трейт вспомогательного функционала работы с сервисами
 *
 * @package Metaseller\TeletypeApp
 */
trait ServiceTrait
{
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

        throw new TeletypeLibraryException('Undefined property $' . $name);
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

        throw new TeletypeLibraryException('Undefined property $' . $name);
    }
}
