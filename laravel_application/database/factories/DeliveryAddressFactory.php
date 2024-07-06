<?php
/**
 * File name: DeliveryAddressFactory.php
 * Last modified: 2024.01.06 at 17:33:40
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\DeliveryAddress;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeliveryAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "description" => $this->faker->sentence,
            "address" => $this->faker->address,
            "latitude" => $this->faker->latitude,
            "longitude" => $this->faker->longitude,
            "is_default" => $this->faker->boolean,
            "user_id" => $this->faker->numberBetween(1, 6),
        ];
    }
}
