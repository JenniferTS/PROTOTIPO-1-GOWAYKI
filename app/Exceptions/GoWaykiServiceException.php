<?php

namespace App\Exceptions;

use Exception;

class GoWaykiServiceException extends Exception
{
    public function __construct(string $message = 'No pudimos completar esta acción. Intenta nuevamente en unos minutos.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
