<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HardwareScannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'is_active' => $this->is_active
        ];
    }
}
