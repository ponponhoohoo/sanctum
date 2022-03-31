<?php

namespace Database\Factories;
use App\Models\Article;
use App\Models\Tagname;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => Article::all()->random()->id,
            'tag_id' => Tagname::all()->random()->id,
        ];
    }
}
