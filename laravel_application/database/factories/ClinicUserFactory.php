<?php
/*
 * File name: ClinicUserFactory.php
 * Last modified: 2024.05.03 at 19:13:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\ClinicUser;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClinicUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomElement([2, 3, 4]),
            //"user_id" => User::role('clinic_owner')->get()->random()->id,
            'clinic_id' => Clinic::all()->random()->id
        ];
    }
}
