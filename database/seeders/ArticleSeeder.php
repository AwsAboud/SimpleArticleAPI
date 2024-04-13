<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::factory(10)->create();
        // Using factory method to create 3 random users and plucking their IDs
         $authorIds = Author::factory(3)->create()->pluck('id');
        foreach ($articles as $article){
            $article->authors()->attach($authorIds);
        }
        Article::factory(3)->create();
    }
}
