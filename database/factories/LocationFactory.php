<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    public function definition(): array
    {
        $districts = [
            'İzmit' => ['Yenişehir', 'Cedit', 'Alikahya', 'Karabaş', 'Ömerağa'],
            'Gebze' => ['Osman Yılmaz', 'Mustafapaşa', 'Muallimköy', 'Cumhuriyet'],
            'Darıca' => ['Bağlarbaşı', 'Emek', 'Fevzi Çakmak'],
            'Gölcük' => ['Değirmendere', 'Merkez', 'Yazlık'],
            'Çayırova' => ['Şekerpınar', 'Akse', 'Atatürk'],
            'Derince' => ['Yeni Mahalle', 'Çenedağ'],
            'Kartepe' => ['Uzunçiftlik', 'Yeniköy'],
            'Körfez' => ['Yeniköy', 'Hereke'],
        ];

        $district = $this->faker->randomElement(array_keys($districts));

        return [
            'province' => 'Kocaeli',
            'district' => $district,
            'neighborhood' => $this->faker->randomElement($districts[$district]),
        ];
    }
}
