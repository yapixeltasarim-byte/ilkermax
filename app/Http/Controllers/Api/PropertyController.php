<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'listing_type' => ['required', 'in:sale,rent'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'in:TRY,USD,EUR'],
            'province' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'rooms' => ['nullable', 'string', 'max:255'],
            'area_net' => ['nullable', 'integer', 'min:0'],
            'area_gross' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'floor' => ['nullable', 'integer'],
            'total_floors' => ['nullable', 'integer', 'min:0'],
            'building_age' => ['nullable', 'integer', 'min:0'],
            'heating_type' => ['nullable', 'string', 'max:255'],
            'furnished' => ['nullable', 'boolean'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $location = Location::firstOrCreate([
            'province' => $data['province'],
            'district' => $data['district'],
            'neighborhood' => $data['neighborhood'] ?? null,
        ]);

        $category = null;
        if (! empty($data['category'])) {
            $category = Category::firstOrCreate(['name' => $data['category']]);
        }

        $title = $data['title'] ?? $this->generateTitle($data, $category);

        $property = Property::create([
            'title' => $title,
            'description' => $data['description'] ?? null,
            'listing_type' => $data['listing_type'],
            'status' => 'published',
            'price' => $data['price'],
            'currency' => $data['currency'] ?? 'TRY',
            'category_id' => $category?->id,
            'location_id' => $location->id,
            'area_gross' => $data['area_gross'] ?? null,
            'area_net' => $data['area_net'] ?? null,
            'rooms' => $data['rooms'] ?? null,
            'bathrooms' => $data['bathrooms'] ?? null,
            'floor' => $data['floor'] ?? null,
            'total_floors' => $data['total_floors'] ?? null,
            'building_age' => $data['building_age'] ?? null,
            'heating_type' => $data['heating_type'] ?? null,
            'furnished' => $data['furnished'] ?? false,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'published_at' => now(),
        ]);

        return response()->json([
            'id' => $property->id,
            'upload_url' => route('api.properties.images.store', $property),
        ], 201);
    }

    public function storeImages(Request $request, Property $property)
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'max:5120'],
        ]);

        $path = $data['image']->store('properties', 'public');

        $image = PropertyImage::create([
            'property_id' => $property->id,
            'path' => $path,
            'is_cover' => ! $property->images()->where('is_cover', true)->exists(),
            'sort_order' => $property->images()->count(),
        ]);

        return response()->json([
            'id' => $image->id,
            'url' => Storage::disk('public')->url($path),
        ], 201);
    }

    private function generateTitle(array $data, ?Category $category): string
    {
        $parts = array_filter([
            $data['listing_type'] === 'sale' ? 'Satılık' : 'Kiralık',
            $data['rooms'] ?? null,
            $category?->name ?? 'Emlak',
        ]);

        return implode(' ', $parts).' - '.Str::upper(Str::random(4));
    }
}
