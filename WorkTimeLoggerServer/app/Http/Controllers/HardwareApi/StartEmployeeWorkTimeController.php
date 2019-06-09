<?php

namespace App\Http\Controllers\HardwareApi;

use App\Domain\Employee\EmployeeAggregate;
use App\Http\Responses\HardwareApi\CardQueryResponse;
use App\Http\Responses\HardwareApi\WorkStartedResponse;
use App\Models\Card;
use App\Models\WorkLog\OpenEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class StartEmployeeWorkTimeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $card_identifier
     *
     * @return WorkStartedResponse
     */
    public function __invoke(Request $request, $card_identifier)
    {
        $card = Card::where('identifier', $card_identifier)->firstOrFail();

        $entry_uuid = Str::uuid();
        
        $card->Employee->getAggregate()
            ->startWork($entry_uuid, now(), $request->user())
            ->persist();
        
        $entry = OpenEntry::byUuid($entry_uuid);
        
        return new WorkStartedResponse($entry);
    }
}
