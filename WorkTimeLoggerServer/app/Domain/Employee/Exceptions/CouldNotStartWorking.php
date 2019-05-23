<?php


namespace App\Domain\Employee\Exceptions;

use DomainException;

class CouldNotStartWorking extends DomainException
{
    public static function validEntryAlreadyExist(): self
    {
        return new static("There is valid, already started entry.");
    }
}