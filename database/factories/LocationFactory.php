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
            'Kadıköy' => ['Moda', 'Caferağa', 'Fenerbahçe'],
            'Beşiktaş' => ['Levent', 'Etiler', 'Bebek'],
            'Üsküdar' => ['Acıbadem', 'Altunizade', 'Kuzguncuk'],
            'Şişli' => ['Nişantaşı', 'Mecidiyeköy', 'Teşvikiye'],
            'Ataşehir' => ['Barbaros', 'İçerenköy', 'Küçükbakkalköy'],
            'Bakırköy' => ['Yeşilköy', 'Ataköy', 'Florya'],
            'Maltepe' => ['Bağlarbaşı', 'Küçükyalı', 'Cevizli'],
        ];

        $district = $this->faker->randomElement(array_keys($districts));

        return [
            'province' => 'İstanbul',
            'district' => $district,
            'neighborhood' => $this->faker->randomElement($districts[$district]),
        ];
    }
}
