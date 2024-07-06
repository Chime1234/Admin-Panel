<?php
/**
 * File name: SpecialityFactory.php
 * Last modified: 2021.01.06 at 17:33:39
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

use App\Models\Speciality;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Speciality::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence
    ];
});
