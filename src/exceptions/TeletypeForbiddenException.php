<?php

namespace Metaseller\TeletypeApp\exceptions;

use Exception;

/**
 * Класс API-исключения, возникшего вследствие ошибки доступа
 *
 * @package Metaseller\TeletypeApp
 */
class TeletypeForbiddenException extends TeletypeBaseException
{
    /**
     * @inheritDoc
     */
    public function __construct($message = null, $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
