<?php
/*
 * File name: PatientFactory.php
 * Last modified: 2024.08.04 at 18:10:26
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'first_name' =>  fake()->randomElement(
                    ["Patty","Constance","Rita","Anne","Polly","Ester","Ivan","Laura","Coral","Ray","Polly","Wayde","Mark","Penny"]) . " ",
            'last_name' => fake()->randomElement(
                ["O’Furniture","Noring","Book","Teak","Pipe","La Vista","Itchinos","Norda","Trout","Manta","Norma","Polly","Leeva","Waites"]),
            'user_id' => User::role('customer')->get()->random()->id,
            'phone_number' => fake()->phoneNumber,
            'mobile_number' => fake()->phoneNumber,
            'age' => fake()->numberBetween(10,70),
            'gender' => fake()->randomElement(["Male","Female"]),
            'weight' => fake()->randomFloat(2,4,100),
            'height' => fake()->numberBetween(30,240),
            'medical_history' => fake()->text,
            'notes' => fake()->text,
        ];
    }
}
