<?php
/*
 * File name: FaqCategoryFactory.php
 * Last modified: 2024.01.11 at 22:36:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\FaqCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class FaqCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FaqCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $names = ['Service', 'Payment', 'Support', 'Providers', 'Misc'];
        return [
            'name' => $this->faker->randomElement($names),
        ];
    }
}
