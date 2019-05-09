<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\HardwareScanner;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(HardwareScanner::class, function (Faker $faker) {
    return [
        'api_token' => Str::random(60),
        'name' => $faker->jobTitle,
        'is_active' => $faker->boolean,
    ];
});

$factory->state(HardwareScanner::class, 'active', [
    'is_active' => true,
]);

$factory->state(HardwareScanner::class, 'inactive', [
    'is_active' => false,
]);
