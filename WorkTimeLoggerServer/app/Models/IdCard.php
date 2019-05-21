<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

class IdCard extends Model
{
    use Uuidable;
    
    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
