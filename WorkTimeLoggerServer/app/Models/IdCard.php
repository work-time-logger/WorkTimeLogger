<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\IdCard
 *
 * @property int $id
 * @property string $employee_uuid
 * @property string $uuid
 * @property string $rfid_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard whereEmployeeUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard whereRfidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\IdCard whereUuid($value)
 * @mixin \Eloquent
 */
class IdCard extends Model
{
    use Uuidable;
    
    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_uuid');
    }
}
