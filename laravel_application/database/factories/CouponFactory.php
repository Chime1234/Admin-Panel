<?php
/*
 * File name: CouponFactory.php
 * Last modified: 2024.05.03 at 20:46:36
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Coupon;
use App\Models\Doctor;
use App\Models\Speciality;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $code = ["PROMO10", "OFF20", "CHRISTMAS", "PROMO30", "OFF50"];
        $clinics = Clinic::all()->pluck('id')->toArray();
        $doctors = Doctor::all()->pluck('id')->toArray();
        $specialities = Speciality::all()->pluck('id')->toArray();

        return [
            'code' => $this->faker->unique()->randomElement($code),
            'discount' => $this->faker->randomElement([5, 10, 15, 20, 25]),
            'discount_type' => $this->faker->randomElement(["percent", "fixed"]),
            'description' => $this->faker->sentence(),
            'expires_at' => $this->faker->dateTimeBetween('75 hours', '80 hours'),
            'enabled' => $this->faker->boolean(),
            'clinics' => $this->faker->randomElements($clinics, 2),
            'doctors' => $this->faker->randomElements($doctors, 2),
            'specialities' => $this->faker->randomElements($specialities, 2),
        ];
    }
}
