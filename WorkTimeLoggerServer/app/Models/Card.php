<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\Card
 *
 * @property int $id
 * @property string $employee_uuid
 * @property string $identifier
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereEmployeeUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Card extends Model
{
    protected $guarded = ['id'];
    
    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_uuid');
    }
}
