<?php


namespace App\Http\Routers;


use Illuminate\Http\Request;
use Route;
use SebastiaanLuca\Router\Routers\Router;

class ApiRouter extends Router
{
    /**
     * Map the routes.
     */
    public function map(): void 
    {
        $this->router->group(['middleware' => ['api'], 'prefix' => 'api'], function () {

            $this->router->middleware('auth:api')->get('/user', function (Request $request) {
                return $request->user();
            });

        });
    }
}