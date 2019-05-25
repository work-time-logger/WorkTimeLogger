<?php


namespace App\Domain\Employee\Exceptions;


use DomainException;

class CouldNotCreateEmployee extends DomainException
{
    public static function employeeAlreadyExist(): self
    {
        return new static("Employee with provided ID already exists.");
    }
}