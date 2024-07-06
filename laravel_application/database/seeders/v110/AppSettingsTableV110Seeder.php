<?php
/*
 * File name: AppSettingsTableV110Seeder.php
 * Last modified: 2022.04.16 at 13:30:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders\v110;

use Exception;
use DB;
use Illuminate\Database\Seeder;

class AppSettingsTableV110Seeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {

        try {
            DB::table('app_settings')->insert(
                array(
                    'id' => 150,
                    'key' => 'clinic_app_name',
                    'value' => 'Clinic Management',
                )
            );
        }catch (Exception $e){}



    }
}
