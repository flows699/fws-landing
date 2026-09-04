<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_path')
                    ->label('Borító')
                    ->disk('public'),
                TextColumn::make('title')
                    ->label('Cím')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Megjelenés')
                    ->date('Y.m.d')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Publikált')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Publikálás')
                    ->placeholder('Mind')
                    ->trueLabel('Csak publikált')
                    ->falseLabel('Csak rejtett'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
