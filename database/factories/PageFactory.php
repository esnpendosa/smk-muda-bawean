<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = $this->faker->words(2, true);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => '<p>' . $this->faker->paragraph() . '</p>',
        ];
    }
}
