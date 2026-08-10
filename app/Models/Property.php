<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'listing_type', 'status',
        'price', 'currency', 'category_id', 'location_id', 'agent_id',
        'area_gross', 'area_net', 'rooms', 'bathrooms', 'floor', 'total_floors',
        'building_age', 'heating_type', 'furnished', 'latitude', 'longitude',
        'is_featured', 'views', 'published_at',
    ];

    protected $casts = [
        'furnished' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (Property $property) {
            if (! $property->slug) {
                $location = $property->location;

                $slugSource = collect([
                    $property->title,
                    $location?->district,
                    $location?->neighborhood,
                ])->filter()->implode(' ');

                $property->slug = Str::slug($slugSource, '-', 'tr').'-'.Str::lower(Str::random(5));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'property_feature');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    protected function coverImage(): Attribute
    {
        return Attribute::get(function () {
            if ($this->relationLoaded('images')) {
                return $this->images->firstWhere('is_cover', true) ?? $this->images->first();
            }

            return $this->images()->orderByDesc('is_cover')->orderBy('sort_order')->first();
        });
    }
}
