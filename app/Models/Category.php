<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id'];

    /**
     * İsme göre eşleşen heroicon bileşen adı (kategori butonlarında kullanılır).
     */
    protected function icon(): Attribute
    {
        return Attribute::get(fn () => match ($this->name) {
            'Daire' => 'heroicon-o-building-office-2',
            'Villa' => 'heroicon-o-home-modern',
            'Müstakil Ev' => 'heroicon-o-home',
            'Arsa' => 'heroicon-o-map',
            'İşyeri' => 'heroicon-o-briefcase',
            'Residence' => 'heroicon-o-building-office',
            'Yazlık' => 'heroicon-o-sun',
            default => 'heroicon-o-building-office-2',
        });
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (! $category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
