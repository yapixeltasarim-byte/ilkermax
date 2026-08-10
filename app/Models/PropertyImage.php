<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyImage extends Model
{
    protected $fillable = ['property_id', 'path', 'is_cover', 'sort_order'];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(
            fn () => Str::startsWith($this->path, ['http://', 'https://'])
                ? $this->path
                : Storage::disk('public')->url($this->path)
        );
    }
}
