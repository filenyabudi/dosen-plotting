<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Matakuliah;

class MatakuliahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Matakuliah::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'kode_mk' => $this->faker->word(),
            'nama_mk' => $this->faker->text(),
            'sks' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
