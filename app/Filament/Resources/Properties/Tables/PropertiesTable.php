<?php

namespace App\Filament\Resources\Properties\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PropertiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('listing_type')
                    ->label('Tip')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'sale' ? 'Satılık' : 'Kiralık')
                    ->color(fn (string $state) => $state === 'sale' ? 'success' : 'info'),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => 'Taslak',
                        'pending' => 'Onay Bekliyor',
                        'published' => 'Yayınlandı',
                        'sold' => 'Satıldı',
                        'rented' => 'Kiralandı',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'published' => 'success',
                        'sold', 'rented' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('price')
                    ->label('Fiyat')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('location.district')
                    ->label('Konum')
                    ->sortable(),
                TextColumn::make('agent.name')
                    ->label('Danışman'),
                IconColumn::make('is_featured')
                    ->label('Öne Çıkan')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('listing_type')
                    ->label('İlan Tipi')
                    ->options([
                        'sale' => 'Satılık',
                        'rent' => 'Kiralık',
                    ]),
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'draft' => 'Taslak',
                        'pending' => 'Onay Bekliyor',
                        'published' => 'Yayınlandı',
                        'sold' => 'Satıldı',
                        'rented' => 'Kiralandı',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->options(fn () => Category::query()->pluck('name', 'id')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
