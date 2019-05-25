<?php


namespace App\Domain\Scanner\Events;


use Spatie\EventProjector\ShouldBeStored;

class ScannerCreated implements ShouldBeStored
{
    /**
     * @var string
     */
    public $name;

    /**
     * ScannerCreated constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {

        $this->name = $name;
    }
}