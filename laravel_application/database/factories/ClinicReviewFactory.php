<?php
/*
 * File name: ClinicReviewFactory.php
 * Last modified: 2024.02.04 at 18:49:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\ClinicReview;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClinicReview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "review" => fake()->realText(100),
            "rate" => fake()->numberBetween(1, 5),
            "user_id" => User::role('customer')->get()->random()->id,
            "clinic_id" => Clinic::all()->random()->id,
        ];
    }
}
