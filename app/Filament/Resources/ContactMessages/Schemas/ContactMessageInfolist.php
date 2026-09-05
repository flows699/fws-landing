<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Név'),
                TextEntry::make('email')
                    ->label('E-mail')
                    ->copyable()
                    ->copyMessage('E-mail cím másolva'),
                TextEntry::make('created_at')
                    ->label('Beérkezett')
                    ->dateTime('Y.m.d H:i'),
                TextEntry::make('read_at')
                    ->label('Olvasva')
                    ->dateTime('Y.m.d H:i')
                    ->placeholder('Még nem olvasott'),
                TextEntry::make('ip_address')
                    ->label('IP-cím')
                    ->placeholder('Ismeretlen'),
                TextEntry::make('message')
                    ->label('Üzenet')
                    ->columnSpanFull(),
            ]);
    }
}
