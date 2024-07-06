<?php
/*
 * File name: ModelHasPermissionsTableSeeder.php
 * Last modified: 2021.03.01 at 21:23:22
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ModelHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('model_has_permissions')->delete();
    }
}
