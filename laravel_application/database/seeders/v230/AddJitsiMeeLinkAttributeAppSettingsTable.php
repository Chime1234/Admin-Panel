<?php

namespace Database\Seeders\v230;

use Exception;
use Illuminate\Database\Seeder;
use DB;

class AddJitsiMeeLinkAttributeAppSettingsTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::table('app_settings')->insert(
                array(
                    'key' => 'jitsi_meet_link',
                    'value' => 'https://meet.jit.si/',
                )
            );
        }catch (Exception $e){}
    }
}
