<?php
/*
 * File name: DoctorFactory.php
 * Last modified: 2024.11.15 at 12:38:59
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Doctor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $doctors = [
            'Dr. Darren Elder', 'Dr. Deborah Angel', 'Dr. John Gibbs', 'Dr. Katharine Sofia', 'Dr. Linda Tobin', 'Dr. Marvin Campbell', 'Dr. Olga Barlow', 'Dr. Paul Richard', 'Dr. Ruby Paul', 'Dr. John Perrin', 'Dr. Paul Brient', 'Dr. Lois Di Nominator', 'Dr. Karen Onnabit', 'Dr. Ray Sin', 'Dr. Darren Campbell', 'Dr. Cherry Blossom', 'Dr. Hank R. Cheef', 'Dr. Olive Yew', 'Dr. Toi Story', 'Dr. Rod Knee', 'Dr. Mary Krismass'
        ];
        $price = $this->faker->randomFloat(2, 10, 50);
        $discountPrice = $price - $this->faker->randomFloat(2, 1, 10);

        return [
            'name' => $this->faker->randomElement($doctors),
            'price' => $price,
            'discount_price' => $this->faker->randomElement([$discountPrice, 0]),
            'description' => $this->faker->text,
            'featured' => $this->faker->boolean,
            'enable_appointment' => $this->faker->boolean,
            'available' => $this->faker->boolean,
            'commission' => $this->faker->numberBetween(10, 90),
            'clinic_id' => Clinic::all()->random()->id,
            'user_id' => $this->faker->randomElement(User::role('doctor')->pluck('id')),
        ];
    }
}
