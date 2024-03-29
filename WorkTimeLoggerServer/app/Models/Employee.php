<?php

namespace App\Models;

use App\Domain\Employee\EmployeeAggregate;
use App\Models\WorkLog\DailySummary;
use App\Models\WorkLog\Entry;
use App\Models\WorkLog\OpenEntry;
use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\Employee
 *
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkLog\DailySummary[] $DailySummaries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkLog\Entry[] $Entries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Card[] $IdCards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkLog\OpenEntry[] $OpenEntries
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Employee whereUuid($value)
 * @mixin \Eloquent
 */
class Employee extends Model
{
    use Uuidable;

    public function OpenEntries()
    {
        return $this->hasMany(OpenEntry::class, 'employee_uuid')->latest('start');
    }

    public function Entries()
    {
        return $this->hasMany(Entry::class, 'employee_uuid')->latest('start');
    }

    public function DailySummaries()
    {
        return $this->hasMany(DailySummary::class, 'employee_uuid')->latest('day');
    }

    public function IdCards()
    {
        return $this->hasMany(Card::class, 'employee_uuid')->latest('day');
    }

    public function getAggregate()
    {
        return EmployeeAggregate::retrieve($this->uuid);
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
