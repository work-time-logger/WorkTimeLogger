<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HardwareScanner extends Model
{

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token',
    ];
}
