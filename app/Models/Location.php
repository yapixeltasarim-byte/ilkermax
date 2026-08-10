<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['province', 'district', 'neighborhood', 'slug'];

    protected static function booted(): void
    {
        static::saving(function (Location $location) {
            if (! $location->slug) {
                $location->slug = Str::slug(implode('-', array_filter([
                    $location->province,
                    $location->district,
                    $location->neighborhood,
                ])));
            }
        });
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
