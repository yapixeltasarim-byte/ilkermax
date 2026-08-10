<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $rooms = $this->faker->randomElement(['1+0', '1+1', '2+1', '3+1', '4+1', '5+1']);
        $listingType = $this->faker->randomElement(['sale', 'rent']);
        $areaNet = $this->faker->numberBetween(60, 300);
        $title = $this->faker->randomElement(['Satılık', 'Kiralık']).' '.$rooms.' Daire';

        return [
            'title' => $title,
            'description' => $this->faker->paragraphs(3, true),
            'listing_type' => $listingType,
            'status' => 'published',
            'price' => $listingType === 'sale'
                ? $this->faker->numberBetween(1_500_000, 15_000_000)
                : $this->faker->numberBetween(15_000, 120_000),
            'currency' => 'TRY',
            'category_id' => Category::query()->inRandomOrder()->value('id'),
            'location_id' => Location::query()->inRandomOrder()->value('id'),
            'agent_id' => Agent::query()->inRandomOrder()->value('id'),
            'area_gross' => $areaNet + $this->faker->numberBetween(10, 30),
            'area_net' => $areaNet,
            'rooms' => $rooms,
            'bathrooms' => $this->faker->numberBetween(1, 3),
            'floor' => $this->faker->numberBetween(0, 15),
            'total_floors' => $this->faker->numberBetween(4, 20),
            'building_age' => $this->faker->numberBetween(0, 25),
            'heating_type' => $this->faker->randomElement(['Kombi (Doğalgaz)', 'Merkezi', 'Yerden Isıtma', 'Klima']),
            'furnished' => $this->faker->boolean(30),
            'latitude' => $this->faker->latitude(40.80, 41.15),
            'longitude' => $this->faker->longitude(28.75, 29.35),
            'is_featured' => $this->faker->boolean(20),
            'views' => $this->faker->numberBetween(0, 500),
            'published_at' => now(),
        ];
    }
}
