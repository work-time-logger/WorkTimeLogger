<?php

namespace App\Models\WorkLog;

use App\Models\Employee;
use App\Models\Scanner;
use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\WorkLog\Entry
 *
 * @property string $uuid
 * @property string $employee_uuid
 * @property \Illuminate\Support\Carbon|null $start
 * @property \Illuminate\Support\Carbon|null $end
 * @property int $worked_minutes
 * @property string|null $started_by
 * @property string|null $ended_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee $Employee
 * @property-read \App\Models\Scanner|null $EndedBy
 * @property-read \App\Models\Scanner|null $StartedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereEmployeeUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereEndedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\Entry whereStartedBy($value)
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
        'worked_minutes' => 'int',
    ];

    public function StartedBy()
    {
        return $this->belongsTo(Scanner::class, 'started_by');
    }

    public function EndedBy()
    {
        return $this->belongsTo(Scanner::class, 'ended_by');
    }

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_uuid');
    }

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'uuid';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
