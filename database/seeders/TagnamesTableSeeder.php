<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tagname;
class TagnamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Tagname::factory(20)->create();
    }
}
