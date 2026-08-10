<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Daire', 'Villa', 'Arsa', 'İşyeri', 'Müstakil Ev', 'Residence', 'Yazlık',
        ]);

        return [
            'name' => $name,
        ];
    }
}
