<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Like::factory()->count(15)->create();
    }
}
