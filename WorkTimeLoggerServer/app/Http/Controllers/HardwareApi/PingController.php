<?php

namespace App\Http\Controllers\HardwareApi;

use App\Http\Responses\HardwareApi\PingResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PingController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return PingResponse
     */
    public function __invoke(Request $request)
    {
        return new PingResponse($request->user());
    }
}
