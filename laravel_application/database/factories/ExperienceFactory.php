<?php
/*
 * File name: ExperienceFactory.php
 * Last modified: 2024.01.18 at 15:57:56
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Experience;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Experience::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(127),
            'description' => $this->faker->realText(),
            'doctor_id' => Doctor::all()->random()->id
        ];
    }

    /**
     * Define a state for title more than 127 characters.
     *
     * @return array
     */
    public function title_more_127_char(): array
    {
        return [
            'title' => $this->faker->paragraph(20),
        ];
    }

    /**
     * Define a state for non-existing clinic ID.
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
