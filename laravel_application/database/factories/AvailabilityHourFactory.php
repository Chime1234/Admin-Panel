<?php
/*
 * File name: AvailabilityHourFactory.php
 * Last modified: 2024.04.20 at 11:19:32
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\AvailabilityHour;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AvailabilityHourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AvailabilityHour::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'day' => Str::lower(fake()->randomElement(Carbon::getDays())),
            'start_at' => str_pad(fake()->numberBetween(2, 12), 2, '0', STR_PAD_LEFT) . ":00",
            'end_at' => fake()->numberBetween(13, 23) . ":00",
            'data' => fake()->text(50),
            'doctor_id' => Doctor::all()->random()->id
        ];
    }

    /**
     * Define the 'day_more_16_char' state.
     *
     * @return array
     */
    public function day_more_16_char(): array
    {
        return [
            'day' => fake()->paragraph(3),
        ];
    }

    /**
     * Define the 'end_at_lest_start_at' state.
     *
     * @return array
     */
    public function end_at_lest_start_at(): array
    {
        return [
            'start_at' => fake()->numberBetween(16, 21) . ":20",
            'end_at' => fake()->numberBetween(10, 13) . ":30",
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
