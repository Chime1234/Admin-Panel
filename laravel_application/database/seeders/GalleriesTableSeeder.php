<?php
/*
 * File name: GalleriesTableSeeder.php
 * Last modified: 2021.03.01 at 21:23:22
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GalleriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('galleries')->delete();
        Gallery::factory()->count(20)->create();
    }
}
