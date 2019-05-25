<?php

namespace App\Http\Controllers\HardwareApi;

use App\Domain\Employee\EmployeeAggregate;
use App\Http\Responses\HardwareApi\WorkStartedResponse;
use App\Http\Responses\HardwareApi\WorkStoppedResponse;
use App\Models\IdCard;
use App\Models\WorkLog\Entry;
use App\Models\WorkLog\OpenEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class StopEmployeeWorkTimeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return WorkStoppedResponse
     */
    public function __invoke(Request $request, $rfid_id, $entry_uuid)
    {
        $card = IdCard::where('rfid_id', $rfid_id)->firstOrFail();

        $card->Employee->getAggregate()
            ->stopWork($entry_uuid, now())
            ->persist();

        $entry = Entry::byUuid($entry_uuid);

        return new WorkStoppedResponse($entry);
    }
}
