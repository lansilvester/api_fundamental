<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => fake()->unique()->numerify('2024###'),
            'nama' => fake()->name(),
            'kelas' => fake()->randomElement(['X-A', 'X-B', 'XI-A', 'XI-B', 'XII-A', 'XII-B']),
            'jurusan' => fake()->randomElement(['RPL', 'TKJ', 'MM']),
            'alamat' => fake()->address(),
            'no_telp' => fake()->phoneNumber(),
        ];
    }
}
