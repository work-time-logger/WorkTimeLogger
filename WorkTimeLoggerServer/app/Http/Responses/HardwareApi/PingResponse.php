<?php

namespace App\Http\Responses\HardwareApi;


use App\Http\Resources\HardwareScannerResource;
use App\Models\HardwareScanner;
use KDuma\ContentNegotiableResponses\BaseArrayResponse;

class PingResponse extends BaseArrayResponse
{
    /**
     * @var HardwareScanner
     */
    public $hardwareScanner;

    /**
     * PingResponse constructor.
     *
     * @param HardwareScanner $hardwareScanner
     */
    public function __construct(HardwareScanner $hardwareScanner)
    {
        $this->hardwareScanner = $hardwareScanner;
    }

    protected function getData()
    {
        return new HardwareScannerResource($this->hardwareScanner);
    }
}
