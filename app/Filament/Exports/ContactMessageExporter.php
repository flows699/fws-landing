<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Models\ContactMessage;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ContactMessageExporter extends Exporter
{
    protected static ?string $model = ContactMessage::class;

    /**
     * @return array<int, ExportColumn>
     */
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('Azonosító'),
            // The name, e-mail and message come from the public form, so a
            // leading `=` would run as a formula in a spreadsheet.
            ExportColumn::make('name')
                ->label('Név')
                ->preventFormulaInjection(),
            ExportColumn::make('email')
                ->label('E-mail')
                ->preventFormulaInjection(),
            ExportColumn::make('message')
                ->label('Üzenet')
                ->preventFormulaInjection(),
            ExportColumn::make('ip_address')
                ->label('IP-cím'),
            ExportColumn::make('read_at')
                ->label('Olvasva'),
            ExportColumn::make('created_at')
                ->label('Beérkezett'),
        ];
    }

    /**
     * @return array<int, ExportFormat>
     */
    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
            ExportFormat::Xlsx,
        ];
    }

    public function getFileName(Export $export): string
    {
        return 'uzenetek-'.now()->format('Y-m-d');
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = "Az üzenetek exportja elkészült, {$export->successful_rows} sorral.";

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= " {$failedRowsCount} sort nem sikerült exportálni.";
        }

        return $body;
    }
}
