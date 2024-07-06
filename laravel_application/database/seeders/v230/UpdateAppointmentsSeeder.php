<?php

namespace Database\Seeders\v230;
use DB;
use Illuminate\Database\Seeder;

class UpdateAppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $appointments = DB::table('appointments')->whereNotNull('doctor')->get();

        foreach ($appointments as $appointment) {
            // Parse the doctor information
            $doctor = json_decode($appointment->doctor, true); // true to get associative array
            if ($doctor && isset($doctor['id'])) {
                DB::table('appointments')->where('id', $appointment->id)->update([
                    'doctor_id' => $doctor['id']
                ]);
            }
        }
    }
}
