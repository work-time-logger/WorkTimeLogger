<?php

namespace App\Http\Responses\HardwareApi;


use App\Http\Resources\HardwareScannerResource;
use App\Models\Employee;
use App\Models\Scanner;
use App\Models\WorkLog\Entry;
use App\Models\WorkLog\OpenEntry;
use KDuma\ContentNegotiableResponses\BaseArrayResponse;

class WorkStoppedResponse extends BaseArrayResponse
{
    /**
     * @var Entry
     */
    public $entry;
    
    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    protected function getData()
    {
        return [
            'entry' => $this->entry->uuid,
            'start' => $this->entry->start->format('Y-m-d H:i:s'),
            'end' => $this->entry->end->format('Y-m-d H:i:s'),
            'worked_minutes' => $this->entry->worked_minutes,
        ];
    }
}
