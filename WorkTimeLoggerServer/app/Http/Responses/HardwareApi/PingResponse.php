<?php

namespace App\Http\Responses\HardwareApi;


use App\Http\Resources\HardwareScannerResource;
use App\Models\Scanner;
use KDuma\ContentNegotiableResponses\BaseArrayResponse;

class PingResponse extends BaseArrayResponse
{
    /**
     * @var Scanner
     */
    public $hardwareScanner;

    /**
     * PingResponse constructor.
     *
     * @param Scanner $hardwareScanner
     */
    public function __construct(Scanner $hardwareScanner)
    {
        $this->hardwareScanner = $hardwareScanner;
    }

    protected function getData()
    {
        return new HardwareScannerResource($this->hardwareScanner);
    }
}
