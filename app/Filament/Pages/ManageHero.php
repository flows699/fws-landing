<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\HeroSection;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageHero extends Page
{
    protected string $view = 'filament.pages.manage-hero';

    protected static ?string $slug = 'hero';

    protected static ?string $title = 'Hero szekció';

    protected static ?string $navigationLabel = 'Hero szekció';

    protected static string|UnitEnum|null $navigationGroup = 'Beállítások';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Tartalom')
                        ->schema([
                            TextInput::make('title')
                                ->label('Főcím')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('subtitle')
                                ->label('Alcím')
                                ->rows(3)
                                ->maxLength(500),
                            FileUpload::make('image_path')
                                ->label('Háttérkép')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory('hero')
                                ->visibility('public')
                                // A hero teljes szélességű háttérkép, ezért nagyobb a limit,
                                // mint a referenciák borítóképénél.
                                ->maxSize(4096),
                        ]),
                    Section::make('Gombok')
                        ->columns(2)
                        ->schema([
                            TextInput::make('cta_primary_label')
                                ->label('Elsődleges gomb felirata')
                                ->maxLength(255),
                            // Nincs url() validáció: a designban horgony-linkek vannak (#kapcsolat).
                            TextInput::make('cta_primary_url')
                                ->label('Elsődleges gomb linkje')
                                ->maxLength(255),
                            TextInput::make('cta_secondary_label')
                                ->label('Másodlagos gomb felirata')
                                ->maxLength(255),
                            TextInput::make('cta_secondary_url')
                                ->label('Másodlagos gomb linkje')
                                ->maxLength(255),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Mentés')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $record = $this->getRecord();
        $record->fill($this->form->getState());
        $record->save();

        Notification::make()
            ->success()
            ->title('Hero szekció mentve.')
            ->send();
    }

    /**
     * The hero section the form edits, unsaved when the table is still empty.
     */
    public function getRecord(): HeroSection
    {
        return HeroSection::current();
    }
}
