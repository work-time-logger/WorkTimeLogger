<?php

namespace App\Models\WorkLog;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class DailySummary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'day' => 'date',
        'worked_minutes' => 'int',
    ];

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
