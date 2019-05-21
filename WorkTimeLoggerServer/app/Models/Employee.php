<?php

namespace App\Models;

use App\Models\WorkLog\Entry;
use App\Models\WorkLog\OpenEntry;
use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

class Employee extends Model
{
    use Uuidable;

    public function OpenEntries()
    {
        return $this->hasMany(OpenEntry::class, 'employee_id');
    }

    public function Entries()
    {
        return $this->hasMany(Entry::class, 'employee_id');
    }
}
