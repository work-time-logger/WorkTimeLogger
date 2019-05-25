<?php


namespace App\Domain\Scanner\Events;


use Spatie\EventProjector\ShouldBeStored;

class ApiTokenRegenerated implements ShouldBeStored
{
    /**
     * @var string
     */
    public $api_token;

    /**
     * @param string $api_token
     */
    public function __construct(string $api_token)
    {

        $this->api_token = $api_token;
    }
}