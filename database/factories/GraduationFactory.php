<?php

namespace Database\Factories;

use App\Models\Graduation;
use Illuminate\Database\Eloquent\Factories\Factory;

class GraduationFactory extends Factory
{
    protected $model = Graduation::class;

    public function definition(): array
    {
        return [
            'academic_year' => '2024/2025',
            'student_name' => $this->faker->name(),
            'exam_number' => $this->faker->unique()->bothify('EXAM-#####'),
            'program_keahlian' => $this->faker->randomElement(['Teknik Komputer Jaringan', 'Akuntansi', 'Multimedia']),
            'status_kelulusan' => $this->faker->randomElement(['LULUS', 'TIDAK LULUS']),
        ];
    }
}
