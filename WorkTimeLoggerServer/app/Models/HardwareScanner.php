<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use KDuma\Eloquent\Uuidable;

/**
 * App\Models\HardwareScanner
 *
 * @property int $id
 * @property string $uuid
 * @property string $api_token
 * @property string $name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner whereGuid($guid)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HardwareScanner whereUuid($value)
 * @mixin \Eloquent
 */
class HardwareScanner extends Model
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
}
