<?php


namespace App\Http\Routers;


use Illuminate\Http\Request;
use SebastiaanLuca\Router\Routers\Router;

class HardwareApiRouter extends Router
{
    /**
     * Map the routes.
     */
    public function map(): void 
    {
        $this->router->group(['middleware' => ['hardware_api'], 'prefix' => 'hw'], function () {

//            $this->router->get('/users', function () {
//
//                return view('users.index');
//
//            });
            
            $this->router->middleware('auth:hardware_api')->get('/ping', function (Request $request) {
                return $request->user();
            });

        });
    }
}