<?php

namespace Database\Seeders\v140;
use Illuminate\Database\Seeder;

class Updatev140Seeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleHasPermissionTableV140Seeder::class);
    }
}
