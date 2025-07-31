<?php

namespace Metaseller\TeletypeApp\exceptions;

use Exception;

/**
 * Класс API-исключения, возникшего вследствие ошибки подготовки или выполнения запроса
 *
 * @package Metaseller\TeletypeApp
 */
class TeletypeBadRequestBaseException extends TeletypeBaseException
{
    /**
     * @inheritDoc
     */
    public function __construct($message = null, $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
