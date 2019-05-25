<?php


namespace App\Domain\Scanner\Exceptions;


use DomainException;
use Throwable;

class ScannerDoesntExistException extends DomainException
{
    public function __construct($message = "Scanner doesn't exist.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}