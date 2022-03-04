<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => "お笑い",
        ]);
        DB::table('categories')->insert([
            'name' => "ゲーム",
        ]);
        DB::table('categories')->insert([
            'name' => "動物",
        ]);
        DB::table('categories')->insert([
            'name' => "風景",
        ]);
        DB::table('categories')->insert([
            'name' => "スポーツ",
        ]);
    }
}
