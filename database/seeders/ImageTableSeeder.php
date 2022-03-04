<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('images')->insert([
            'article_id' => 1,
            'user_id' => 11,
            'path' => "test.jpg",
        ]);

        DB::table('images')->insert([
            'article_id' => 4,
            'user_id' => 6,
            'path' => "test.jpg",
        ]);
    }
}
