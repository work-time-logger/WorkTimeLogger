<?php

namespace App\Models\WorkLog;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WorkLog\DailySummary
 *
 * @property int $id
 * @property string $employee_uuid
 * @property \Illuminate\Support\Carbon $day
 * @property int $worked_minutes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary whereEmployeeUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\DailySummary whereWorkedMinutes($value)
 * @mixin \Eloquent
 */
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
        return $this->belongsTo(Employee::class, 'employee_uuid');
    }
}
