<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Location;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::published()->with(['images', 'location', 'category']);

        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->string('listing_type'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('province') || $request->filled('district') || $request->filled('neighborhood')) {
            $query->whereHas('location', function ($locationQuery) use ($request) {
                $locationQuery
                    ->when($request->filled('province'), fn ($q) => $q->where('province', $request->string('province')))
                    ->when($request->filled('district'), fn ($q) => $q->where('district', $request->string('district')))
                    ->when($request->filled('neighborhood'), fn ($q) => $q->where('neighborhood', $request->string('neighborhood')));
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->integer('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->integer('max_price'));
        }

        if ($request->filled('rooms')) {
            $query->where('rooms', $request->string('rooms'));
        }

        match ($request->string('sort')->toString()) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $properties = $query->paginate(12)->withQueryString();

        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        $locations = Location::orderBy('district')->get();

        return view('properties.index', compact('properties', 'categories', 'locations'));
    }

    public function show(Property $property)
    {
        abort_unless($property->status === 'published', 404);

        $property->load(['images', 'category', 'location', 'agent', 'features']);
        $property->increment('views');

        return view('properties.show', compact('property'));
    }
}
