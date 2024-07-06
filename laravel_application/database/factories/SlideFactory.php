<?php
/*
 * File name: SlideFactory.php
 * Last modified: 2024.05.03 at 11:59:39
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $doctor = fake()->boolean;
        $array = [
            'order' => fake()->numberBetween(0, 5),
            'text' => fake()->sentence(4),
            'button' => fake()->randomElement(['Discover It', 'Book Now', 'Get Discount']),
            'text_position' => fake()->randomElement(['start', 'end', 'center']),
            'text_color' => '#25d366',
            'button_color' => '#25d366',
            'background_color' => '#ccccdd',
            'indicator_color' => '#25d366',
            'image_fit' => 'cover',
            'doctor_id' => $doctor ? Doctor::all()->random()->id : null,
            'clinic_id' => !$doctor ? Clinic::all()->random()->id : null,
            'enabled' => fake()->boolean,
        ];

        return $array;
    }
}
