<?php

namespace App\Http\Responses\HardwareApi;


use App\Http\Resources\HardwareScannerResource;
use App\Models\Employee;
use App\Models\HardwareScanner;
use App\Models\WorkLog\OpenEntry;
use KDuma\ContentNegotiableResponses\BaseArrayResponse;

class WorkStartedResponse extends BaseArrayResponse
{
    /**
     * @var OpenEntry
     */
    public $entry;
    
    public function __construct(OpenEntry $entry)
    {
        $this->entry = $entry;
    }

    protected function getData()
    {
        return [
            'entry' => $this->entry->uuid,
            'start' => $this->entry->start->format('Y-m-d H:i:s')
        ];
    }
}
