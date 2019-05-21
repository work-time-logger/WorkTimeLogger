<?php

namespace App\Models\WorkLog;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\WorkLog\OpenEntry
 *
 * @property int $id
 * @property int $employee_id
 * @property string $uuid
 * @property \Illuminate\Support\Carbon|null $start
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WorkLog\OpenEntry whereUuid($value)
 * @mixin \Eloquent
 */
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
