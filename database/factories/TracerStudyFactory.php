<?php

namespace Database\Factories;

use App\Models\TracerStudy;
use App\Models\Alumni;
use Illuminate\Database\Eloquent\Factories\Factory;

class TracerStudyFactory extends Factory
{
    protected $model = TracerStudy::class;

    public function definition(): array
    {
        return [
            'alumni_id'          => Alumni::factory(),
            'full_name'          => $this->faker->name(),
            'graduation_year'    => $this->faker->year(),
            'education_status'   => $this->faker->randomElement(['kuliah', 'tidak_kuliah']),
            'employment_status'  => $this->faker->randomElement(['bekerja', 'tidak_bekerja']),
        ];
    }
}
