<?php

declare(strict_types=1);

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Cím')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        // A kézzel átírt slugot nem írjuk felül, csak azt, ami még a régi címből származik.
                        if (($get('slug') ?? '') !== Str::slug((string) $old)) {
                            return;
                        }

                        $set('slug', Str::slug((string) $state));
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                FileUpload::make('cover_path')
                    ->label('Borítókép')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('projects')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->required(),
                DatePicker::make('published_at')
                    ->label('Megjelenés dátuma')
                    ->required()
                    ->default(today())
                    ->displayFormat('Y.m.d'),
                Toggle::make('is_published')
                    ->label('Publikált')
                    ->default(true),
            ]);
    }
}
