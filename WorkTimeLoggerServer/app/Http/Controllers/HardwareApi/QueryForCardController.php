<?php

namespace App\Http\Controllers\HardwareApi;

use App\Http\Responses\HardwareApi\CardQueryResponse;
use App\Models\IdCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QueryForCardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $rfid_id
     *
     * @return CardQueryResponse
     */
    public function __invoke(Request $request, $rfid_id)
    {
        $card = IdCard::where('rfid_id', $rfid_id)->firstOrFail();
        
        return new CardQueryResponse($card->Employee);
    }
}
