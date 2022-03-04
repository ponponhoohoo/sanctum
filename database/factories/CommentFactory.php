<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
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
            // $table->integer('user_id');
            //   'article_id' => \App\Models\Article::all()->random()->id,
            'article_id' => 52,
               'title' => $this->faker->title(),
               'content' => $this->faker->text(),
               'user_id' => $this->faker->numberBetween($min = 10, $max = 13),
           ];
    }
}
