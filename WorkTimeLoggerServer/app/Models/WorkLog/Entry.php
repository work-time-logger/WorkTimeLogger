<?php

namespace App\Models\WorkLog;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\WorkLog\Entry
 *
 * @property int $id
 * @property int $employee_id
 * @property string $uuid
 * @property \Illuminate\Support\Carbon|null $start
 * @property \Illuminate\Support\Carbon|null $end
 * @property int $worked_minutes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereWorkedMinutes($value)
 * @mixin \Eloquent
 */
class Entry extends Model
{
    use Uuidable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
