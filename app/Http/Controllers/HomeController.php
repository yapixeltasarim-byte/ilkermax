<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Location;
use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Property::published()
            ->where('is_featured', true)
            ->with(['images', 'location'])
            ->latest()
            ->take(6)
            ->get();

        $latest = Property::published()
            ->with(['images', 'location'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        $locations = Location::orderBy('district')->get();

        return view('home', compact('featured', 'latest', 'categories', 'locations'));
    }
}
