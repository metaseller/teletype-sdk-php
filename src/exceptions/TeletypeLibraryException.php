<?php

namespace Metaseller\TeletypeApp\exceptions;

use Exception;

/**
 * Класс API-исключения, возникшего вследствие ошибки в использовании библиотеки SDK
 *
 * @package Metaseller\TeletypeApp
 */
class TeletypeLibraryException extends TeletypeBaseException
{
    /**
     * @inheritDoc
     */
    public function __construct($message = null, $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
