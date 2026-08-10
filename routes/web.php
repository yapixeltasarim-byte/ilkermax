<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Models\Property;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/ilanlar', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/ilan/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create(route('home'))->setPriority(1.0))
        ->add(Url::create(route('properties.index'))->setPriority(0.8));

    Property::published()->select('slug', 'updated_at')->each(function (Property $property) use ($sitemap) {
        $sitemap->add(
            Url::create(route('properties.show', $property))
                ->setLastModificationDate($property->updated_at)
                ->setPriority(0.6)
        );
    });

    return $sitemap->toResponse(request());
})->name('sitemap');
