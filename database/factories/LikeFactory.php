<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Article;

use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //   'user_id' => \App\Models\User::factory(),
            'user_id' => \App\Models\User::all()->random()->id,
            'article_id' => \App\Models\Article::all()->random()->id,
        ];
    }
}
