<?php


namespace App\Http\Routers;


use Auth;
use Route;
use SebastiaanLuca\Router\Routers\Router;

class WebRouter extends Router
{
    /**
     * Map the routes.
     */
    public function map(): void 
    {
        $this->router->group(['middleware' => ['web'], 'namespace' => 'App\Http\Controllers'], function () {

            Route::get('/', function () {
                return view('welcome');
            });

            Auth::routes();

            Route::get('/home', 'HomeController@index')->name('home');

        });
    }
}