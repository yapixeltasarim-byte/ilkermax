<?php

namespace App\Filament\Resources\Properties\Schemas;

use App\Models\Agent;
use App\Models\Category;
use App\Models\Location;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('İlan Bilgileri')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state).'-'.Str::lower(Str::random(6))))
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label('URL (slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        Select::make('listing_type')
                            ->label('İlan Tipi')
                            ->options([
                                'sale' => 'Satılık',
                                'rent' => 'Kiralık',
                            ])
                            ->required(),
                        Select::make('status')
                            ->label('Durum')
                            ->options([
                                'draft' => 'Taslak',
                                'pending' => 'Onay Bekliyor',
                                'published' => 'Yayınlandı',
                                'sold' => 'Satıldı',
                                'rented' => 'Kiralandı',
                            ])
                            ->default('draft')
                            ->required(),
                        RichEditor::make('description')
                            ->label('Açıklama')
                            ->columnSpanFull(),
                    ]),

                Section::make('Fiyat')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->label('Fiyat')
                            ->numeric()
                            ->required(),
                        Select::make('currency')
                            ->label('Para Birimi')
                            ->options([
                                'TRY' => 'TRY',
                                'USD' => 'USD',
                                'EUR' => 'EUR',
                            ])
                            ->default('TRY')
                            ->required(),
                    ]),

                Section::make('Kategori, Konum ve Danışman')
                    ->columns(3)
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->options(fn () => Category::query()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('location_id')
                            ->label('Konum')
                            ->options(fn () => Location::query()->get()->mapWithKeys(
                                fn (Location $location) => [$location->id => "{$location->district} / {$location->neighborhood}"]
                            ))
                            ->searchable()
                            ->required(),
                        Select::make('agent_id')
                            ->label('Danışman')
                            ->options(fn () => Agent::query()->pluck('name', 'id'))
                            ->searchable(),
                    ]),

                Section::make('Alan Bilgileri')
                    ->columns(3)
                    ->schema([
                        TextInput::make('area_gross')->label('Brüt m²')->numeric(),
                        TextInput::make('area_net')->label('Net m²')->numeric(),
                        TextInput::make('rooms')->label('Oda Sayısı')->placeholder('3+1'),
                        TextInput::make('bathrooms')->label('Banyo Sayısı')->numeric(),
                        TextInput::make('floor')->label('Bulunduğu Kat')->numeric(),
                        TextInput::make('total_floors')->label('Bina Kat Sayısı')->numeric(),
                        TextInput::make('building_age')->label('Bina Yaşı')->numeric(),
                        TextInput::make('heating_type')->label('Isıtma Tipi'),
                        Toggle::make('furnished')->label('Eşyalı'),
                    ]),

                Section::make('Harita')
                    ->columns(2)
                    ->schema([
                        TextInput::make('latitude')->label('Enlem')->numeric(),
                        TextInput::make('longitude')->label('Boylam')->numeric(),
                    ]),

                Section::make('Fotoğraflar')
                    ->schema([
                        Repeater::make('images')
                            ->relationship()
                            ->label('Galeri')
                            ->schema([
                                FileUpload::make('path')
                                    ->label('Fotoğraf')
                                    ->image()
                                    ->disk('public')
                                    ->directory('properties')
                                    ->required(),
                                Grid::make(2)->schema([
                                    Toggle::make('is_cover')->label('Kapak Fotoğrafı'),
                                    TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
                                ]),
                            ])
                            ->orderColumn('sort_order')
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Fotoğraf Ekle'),
                    ]),

                Section::make('Diğer')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_featured')->label('Öne Çıkan İlan'),
                        TextInput::make('views')->label('Görüntülenme')->numeric()->default(0),
                        DateTimePicker::make('published_at')->label('Yayın Tarihi'),
                    ]),
            ]);
    }
}
