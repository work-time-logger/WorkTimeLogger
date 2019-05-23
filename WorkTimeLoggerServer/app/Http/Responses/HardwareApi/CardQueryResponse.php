<?php

namespace App\Http\Responses\HardwareApi;


use App\Domain\Employee\EmployeeAgregate;
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
            'worked_today' => $this->getWorkedToday(),
            'open_entry' => $this->getOpenEntry(),
            'has_invalid_entries' => $this->getHasInvalidEntries(),
        ];
    }

    /**
     * @return null|string
     */
    private function getOpenEntry()
    {
        return optional(
            $this->employee->OpenEntries()
                ->where('start', '>', now()->subHours(EmployeeAgregate::OPEN_WORK_LOG_ENTRY_EXPIRATION_IN_HOURS))
                ->first()
        )->uuid;
    }

    /**
     * @return bool
     */
    private function getHasInvalidEntries()
    {
        return !! $this->employee->OpenEntries()
            ->where('start', '<', now()->subHours(EmployeeAgregate::OPEN_WORK_LOG_ENTRY_EXPIRATION_IN_HOURS))
            ->count();
    }

    /**
     * @return int
     */
    private function getWorkedToday()
    {
        return optional($this->employee->DailySummaries()->where('day', today()->format('Y-m-d'))->first())->worked_minutes ?? 0;
    }
}
