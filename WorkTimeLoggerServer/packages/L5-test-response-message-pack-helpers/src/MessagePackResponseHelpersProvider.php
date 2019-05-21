<?php

namespace KDuma\TestResponseHelpers;

use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use MessagePack\MessagePack;

class MessagePackResponseHelpersProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('testing')) {
            $this->addTestResponseMacros();
        }
    }

    private function addTestResponseMacros()
    {
        TestResponse::macro('assertExactMessagePack', function (array $data) {
            $actual = json_encode(Arr::sortRecursive(
                (array) $this->decodeResponseMessagePack()
            ));

            PHPUnit::assertEquals(json_encode(Arr::sortRecursive($data)), $actual);

            return $this;
        });

        TestResponse::macro('decodeResponseMessagePack', function ($key = null) {
            try{
                $decodedResponse = MessagePack::unpack($this->getContent());
            } catch (\Exception $exception) {
                PHPUnit::fail('Invalid MessagePack was returned from the route.');
            }

            if (is_null($decodedResponse) || $decodedResponse === false) {
                if ($this->exception) {
                    throw $this->exception;
                } else {
                    PHPUnit::fail('Invalid MessagePack was returned from the route.');
                }
            }

            return data_get($decodedResponse, $key);
        });
    }
}
