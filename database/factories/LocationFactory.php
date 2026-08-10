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
        // Kocaeli ilçeleri ve gerçek mahalle isimleri (tr.wikipedia.org ilçe sayfalarından).
        $districts = [
            'İzmit' => ['Yenişehir', 'Cedit', 'Alikahya Cumhuriyet', 'Karabaş', 'Ömerağa', 'Kozluk'],
            'Gebze' => ['Osman Yılmaz', 'Mustafapaşa', 'Muallimköy', 'Cumhuriyet', 'Gaziler', 'Sultan Orhan'],
            'Darıca' => ['Bağlarbaşı', 'Emek', 'Fevziçakmak', 'Bayramoğlu', 'Yenimahalle'],
            'Gölcük' => ['Değirmendere Merkez', 'Yazlık Merkez', 'Cumhuriyet', 'Atatürk', 'Piyalepaşa'],
            'Çayırova' => ['Şekerpınar', 'Akse', 'Atatürk', 'Cumhuriyet', 'Emek'],
            'Derince' => ['Çenedağ', 'Yenikent', 'Fatih', 'Dumlupınar', 'Deniz'],
            'Kartepe' => ['Köseköy', 'Uzunçiftlik', 'Ataevler', 'Dumlupınar', 'İstasyon'],
            'Körfez' => ['Yukarı Hereke', 'Barbaros', 'Fatih', 'Cumhuriyet', 'Yeniyalı'],
        ];

        $district = $this->faker->randomElement(array_keys($districts));

        return [
            'province' => 'Kocaeli',
            'district' => $district,
            'neighborhood' => $this->faker->randomElement($districts[$district]),
        ];
    }
}
