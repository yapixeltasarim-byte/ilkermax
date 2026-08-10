<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;

class ReferenceController extends Controller
{
    /**
     * Bot'un ilan girerken göstereceği kategori listesi.
     */
    public function categories()
    {
        return response()->json(
            Category::whereNull('parent_id')->orderBy('name')->pluck('name')
        );
    }

    /**
     * Bot'un ilan girerken göstereceği il/ilçe/mahalle referans verisi (Kocaeli'nin tamamı).
     * { "İzmit": ["Yenişehir", "Cedit", ...], "Gebze": [...], ... }
     */
    public function locations()
    {
        $grouped = Location::where('province', 'Kocaeli')
            ->orderBy('district')
            ->orderBy('neighborhood')
            ->get(['district', 'neighborhood'])
            ->groupBy('district')
            ->map(fn ($group) => $group->pluck('neighborhood')->filter()->values());

        return response()->json($grouped);
    }
}
