<?php


namespace App\Domain\Scanner\Exceptions;


use DomainException;
use Throwable;

class ScannerAlreadyExists extends DomainException
{
    public function __construct($message = "Scanner already exist.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}