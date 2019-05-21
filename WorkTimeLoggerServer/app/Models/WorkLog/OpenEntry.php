<?php

namespace App\Models\WorkLog;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

class OpenEntry extends Model
{
    use Uuidable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime',
    ];

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
