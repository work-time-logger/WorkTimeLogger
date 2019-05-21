<?php


namespace App\Domain\Employee\Exceptions;

use DomainException;

class CouldNotStopWorking extends DomainException
{
    public static function logDoesntExist(): self
    {
        return new static("Could not stop working, because work log entry is already stopped or doesn't exist.");
    }
    
    public static function logExpired(): self
    {
        return new static("Could not stop working, because work log entry you wanted to stop has expired.");
    }
    
    public static function startIsInFuture(): self
    {
        return new static("End date cannot be before start date.");
    }
}