<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'featured_image' => $this->faker->word(),
            'description' => $this->faker->text(),
            'difficulty_level' => $this->faker->word(),
            'requirements' => $this->faker->words(),
            'learning_objectives' => $this->faker->words(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
