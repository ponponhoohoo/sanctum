<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Article;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Article::class;

    public function definition()
    {
        return [
         //   'user_id' => \App\Models\User::factory(),
            'user_id' => \App\Models\User::all()->random()->id,
            'title' => $this->faker->title(),
            'content' => $this->faker->text(),
            'category' => $this->faker->numberBetween($min = 1, $max = 5),
        ];
    }
    
}
