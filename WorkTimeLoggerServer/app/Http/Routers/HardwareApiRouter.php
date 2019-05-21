<?php


namespace App\Http\Routers;


use App\Http\Controllers\HardwareApi\PingController;
use SebastiaanLuca\Router\Routers\Router;
use Illuminate\Http\Request;

class HardwareApiRouter extends Router
{
    /**
     * Map the routes.
     */
    public function map(): void 
    {
        $this->router->group(['middleware' => ['hardware_api', 'auth:hardware_api'], 'prefix' => 'hw'], function () {
            
            $this->router->get('/ping', PingController::class);

        });
    }
}