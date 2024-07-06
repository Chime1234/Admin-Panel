<?php
/*
 * File name: BookingFactory.php
 * Last modified: 2024.02.16 at 11:47:03
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace Database\Factories;

use App\Models\Address;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\Clinic;
use App\Models\Coupon;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $userId = User::role('customer')->get()->random()->id;
        $address =  fake()->randomElement(Address::where('user_id','=',$userId)->get()->toArray());
        if ($address == null){
            $address = fake()->randomElement(Address::all()->toArray());
        }
        $appointmentStatus =  fake()->randomElement(AppointmentStatus::get()->toArray());
        $coupon =  fake()->randomElement(Coupon::get()->toArray());
        $appointmentAt = fake()->dateTimeBetween('-7 months','70 hours');
        $startAt = fake()->dateTimeBetween('75 hours','80 hours');
        $endsAt = fake()->dateTimeBetween('81 hours','85 hours');
        $patient = fake()->randomElement(Patient::where('user_id', '=', $userId)->get()->toArray());
        if ($patient==null){
            $patient = fake()->randomElement(Patient::all()->toArray()->toArray());
        }
        $clinic = fake()->randomElement(Clinic::where('accepted', '=', '1')->get()->toArray());
        $clinic_id = $clinic['id'];
        $doctor = fake()->randomElement(Doctor::where('clinic_id', '=', $clinic_id)->get()->toArray());
        if ($doctor==null){
            $doctor = fake()->randomElement(Doctor::all()->toArray());
        }

        return [
            'clinic' => $clinic,
            'doctor' => $doctor,
            'patient' => $patient,
            'quantity' => 1,
            'user_id' => $userId,
            'appointment_status_id' => $appointmentStatus['id'],
            'coupon' => $coupon,
            'address' => $address,
            'taxes' => Clinic::find($clinic['id'])->taxes,
            'appointment_at' => $appointmentAt,
            'start_at' => $appointmentStatus['order'] >= 40 ? $startAt : null,
            'ends_at' => $appointmentStatus['order'] >= 50 ? $endsAt : null,
            'hint' => fake()->sentence,
            'cancel' => fake()->boolean(5),
            'online' => fake()->boolean(),
        ];
    }
}
