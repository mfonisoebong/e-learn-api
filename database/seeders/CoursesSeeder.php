<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        Category::factory(5)->create();
        Course::factory(20)->create();
        Module::factory(30)->create();
        Lesson::factory(100)->create();
    }
}
