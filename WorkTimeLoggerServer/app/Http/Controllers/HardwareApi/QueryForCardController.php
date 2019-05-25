<?php

namespace App\Http\Controllers\HardwareApi;

use App\Http\Responses\HardwareApi\CardQueryResponse;
use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QueryForCardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $card_identifier
     *
     * @return CardQueryResponse
     */
    public function __invoke(Request $request, $card_identifier)
    {
        $card = Card::where('identifier', $card_identifier)->firstOrFail();
        
        return new CardQueryResponse($card->Employee);
    }
}
