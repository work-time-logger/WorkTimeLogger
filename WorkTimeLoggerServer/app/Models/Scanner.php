<?php

namespace App\Models;

use App\Domain\Employee\EmployeeAggregate;
use App\Domain\Scanner\ScannerAggregate;
use App\Models\WorkLog\Entry;
use App\Models\WorkLog\OpenEntry;
use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\Scanner
 *
 * @property string $uuid
 * @property string|null $api_token
 * @property string $name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkLog\Entry[] $EndedEntries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkLog\Entry[] $StartedEntries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkLog\OpenEntry[] $StartedOpenEntries
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Scanner whereUuid($value)
 * @mixin \Eloquent
 */
class Scanner extends Model
{
    use Uuidable;
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getAggregate()
    {
        return ScannerAggregate::retrieve($this->uuid);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
    
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
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

    public function StartedOpenEntries()
    {
        return $this->hasMany(OpenEntry::class, 'started_by', 'uuid');
    }

    public function StartedEntries()
    {
        return $this->hasMany(Entry::class, 'started_by', 'uuid');
    }

    public function EndedEntries()
    {
        return $this->hasMany(Entry::class, 'ended_by', 'uuid');
    }
}
