<?php

namespace App\Http\Responses\HardwareApi;


use App\Http\Resources\HardwareScannerResource;
use App\Models\Employee;
use App\Models\HardwareScanner;
use KDuma\ContentNegotiableResponses\BaseArrayResponse;

class CardQueryResponse extends BaseArrayResponse
{
    /**
     * @var Employee
     */
    public $employee;
    
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    protected function getData()
    {
        return [
            'employee' => $this->employee->uuid,
            'first_name' => $this->employee->first_name,
            'last_name' => $this->employee->last_name,
            'worked_today' => 0,
            'open_entry' => null,
            'has_invalid_entries' => false,
        ];
    }
}
