<?php
/*
 * File name: OptionFactory.php
 * Last modified: 2024.03.01 at 21:30:17
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Option;
use App\Models\OptionGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Option::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['10m²', '20m', '30m²', '40m']),
            'description' => fake()->sentence(4),
            'price' => fake()->randomFloat(2, 10, 50),
            'doctor_id' => Doctor::all()->random()->id,
            'option_group_id' => OptionGroup::all()->random()->id,
        ];
    }
}
