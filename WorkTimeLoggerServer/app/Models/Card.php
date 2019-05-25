<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\Card
 *
 * @property-read \App\Models\Employee $Employee
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card query()
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
