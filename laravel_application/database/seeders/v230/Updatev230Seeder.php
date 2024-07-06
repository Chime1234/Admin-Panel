<?php

namespace Database\Seeders\v230;

use Illuminate\Database\Seeder;

class Updatev230Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call( UpdateAppointmentsSeeder::class);
        $this->call( AddJitsiMeeLinkAttributeAppSettingsTable::class);
    }
}
