<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Plotting;

class PlottingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plotting::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'dosen_id' => Dosen::factory(),
            'matakuliah_id' => Matakuliah::factory(),
            'kelas' => $this->faker->word(),
            'semester' => $this->faker->word(),
            'tahun' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
