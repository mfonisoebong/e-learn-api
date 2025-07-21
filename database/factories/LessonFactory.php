<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'duration_in_minutes' => $this->faker->randomElement([34, 30, 45, 70]),
            'description' => $this->faker->text(),
            'content' => $this->faker->paragraph(),
            'document' => 'courses/sample.pdf',
            'video' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'module_id' => Module::inRandomOrder()->first()->id
        ];
    }
}
