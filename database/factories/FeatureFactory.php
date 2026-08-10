<?php

namespace Database\Factories;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Feature>
 */
class FeatureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Asansör', 'Otopark', 'Balkon', 'Güvenlik', 'Yüzme Havuzu',
                'Isı Yalıtımı', 'Doğalgaz', 'Klima', 'Eşyalı', 'Deniz Manzarası',
            ]),
        ];
    }
}
