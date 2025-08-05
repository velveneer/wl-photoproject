<?php

namespace Database\Seeders;

use App\Models\Greeting;
use App\Models\User;
use App\Models\Article;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Article::factory()
            -> count(50)
            -> create();
    }
}
