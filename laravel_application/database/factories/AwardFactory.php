<?php
/*
 * File name: AwardFactory.php
 * Last modified: 2024.05.03 at 20:46:36
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Award;
use App\Models\Clinic;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class AwardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Award::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(100),
            'description' => fake()->realText(),
            'clinic_id' => Clinic::all()->random()->id
        ];
    }

    /**
     * Define the 'title_more_127_char' state.
     *
     * @return array
     */
    public function title_more_127_char(): array
    {
        return [
            'title' => fake()->paragraph(20),
        ];
    }

    /**
     * Define the 'not_exist_clinic_id' state.
     *
     * @return array
     */
    public function not_exist_clinic_id(): array
    {
        return [
            'clinic_id' => 500000, // not exist id
        ];
    }
}
