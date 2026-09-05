<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Filament\Exports\ContactMessageExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Név')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Üzenet')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Beérkezett')
                    ->dateTime('Y.m.d H:i')
                    ->sortable(),
                TextColumn::make('read_at')
                    ->label('Állapot')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state === null ? 'Olvasatlan' : 'Olvasott')
                    ->color(fn (?string $state): string => $state === null ? 'warning' : 'gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Állapot')
                    ->placeholder('Mind')
                    ->trueLabel('Csak olvasatlan')
                    ->falseLabel('Csak olvasott')
                    ->queries(
                        true: fn (Builder $query) => $query->unread(),
                        false: fn (Builder $query) => $query->whereNotNull('read_at'),
                        blank: fn (Builder $query) => $query,
                    ),
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('from')->label('Ettől'),
                        DatePicker::make('until')->label('Eddig'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn (Builder $query, string $date) => $query->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn (Builder $query, string $date) => $query->whereDate('created_at', '<=', $date))),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Exportálás')
                    ->exporter(ContactMessageExporter::class),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Kijelöltek exportálása')
                        ->exporter(ContactMessageExporter::class),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
