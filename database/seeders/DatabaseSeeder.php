<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Location;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Category::factory(7)->create();
        Location::factory(10)->create();
        Agent::factory(5)->create();
        $features = Feature::factory(10)->create();

        Property::factory(15)->create()->each(function (Property $property) use ($features) {
            $imageCount = fake()->numberBetween(2, 5);

            for ($i = 0; $i < $imageCount; $i++) {
                PropertyImage::create([
                    'property_id' => $property->id,
                    'path' => 'https://picsum.photos/seed/'.$property->id.'-'.$i.'/800/600',
                    'is_cover' => $i === 0,
                    'sort_order' => $i,
                ]);
            }

            $property->features()->attach(
                $features->random(fake()->numberBetween(1, 4))->pluck('id')
            );
        });
    }
}
