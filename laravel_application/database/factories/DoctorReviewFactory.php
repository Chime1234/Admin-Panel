<?php
/*
 * File name: DoctorReviewFactory.php
 * Last modified: 2024.02.04 at 18:49:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\DoctorReview;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DoctorReview::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "review" => $this->faker->realText(100),
            "rate" => $this->faker->numberBetween(1, 5),
            "user_id" => User::role('customer')->get()->random()->id,
            "doctor_id" => Doctor::all()->random()->id,
        ];
    }
}
