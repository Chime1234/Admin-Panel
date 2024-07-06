<?php
/**
 * File name: FavoriteFactory.php
 * Last modified: 2024.01.03 at 22:27:45
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Favorite;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Favorite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'doctor_id' => $this->faker->numberBetween(1, 30),
            'user_id' => $this->faker->numberBetween(1, 6)
        ];
    }
}
