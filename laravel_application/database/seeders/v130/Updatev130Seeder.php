<?php
namespace Database\Seeders\v130;
use Illuminate\Database\Seeder;

class Updatev130Seeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(PermissionsTableV130Seeder::class);
    }
}
