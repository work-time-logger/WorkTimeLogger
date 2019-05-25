<?php

namespace App\Http\Controllers\HardwareApi;

use App\Domain\Employee\EmployeeAggregate;
use App\Http\Responses\HardwareApi\CardQueryResponse;
use App\Http\Responses\HardwareApi\WorkStartedResponse;
use App\Models\IdCard;
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
     * @param                          $rfid_id
     *
     * @return WorkStartedResponse
     */
    public function __invoke(Request $request, $rfid_id)
    {
        $card = IdCard::where('rfid_id', $rfid_id)->firstOrFail();

        $entry_uuid = Str::uuid();
        
        $card->Employee->getAgregate()
            ->startWork($entry_uuid, now())
            ->persist();
        
        $entry = OpenEntry::byUuid($entry_uuid);
        
        return new WorkStartedResponse($entry);
    }
}
