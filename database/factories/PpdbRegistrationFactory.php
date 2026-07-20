<?php

namespace Database\Factories;

use App\Models\PpdbRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PpdbRegistrationFactory extends Factory
{
    protected $model = PpdbRegistration::class;

    public function definition(): array
    {
        return [
            'registration_number' => 'PPDB-' . now()->format('Ymd') . '-' . Str::random(4),
            'full_name' => $this->faker->name(),
            'birth_place' => $this->faker->city(),
            'birth_date' => $this->faker->date('Y-m-d', '-15 years'),
            'previous_school' => $this->faker->company() . ' School',
            'parent_name' => $this->faker->name(),
            'phone' => '0812' . $this->faker->numerify('########'),
            'status' => 'menunggu',
        ];
    }
}
