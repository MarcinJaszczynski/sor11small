<?php

namespace App\Filament\Resources\EventTemplateResource\Pages;

use App\Filament\Resources\EventTemplateResource;
use Filament\Resources\Pages\Page;
use App\Models\EventTemplate;
use App\Models\Bus;
use App\Models\TransportType;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Log;

class EventTemplateTransport extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static string $resource = EventTemplateResource::class;
    protected static string $view = 'filament.resources.event-template-resource.pages.event-template-transport';

    public EventTemplate $record;
    public ?array $data = [];
    protected $listeners = ['toggleAvailability', 'updateAvailabilityNote'];

    public function mount($record): void
    {
        if (is_array($record) && isset($record['id'])) {
            $this->record = EventTemplate::findOrFail($record['id']);
        } elseif ($record instanceof EventTemplate) {
            $this->record = $record;
        } else {
            $this->record = EventTemplate::findOrFail($record);
        }

        $this->form->fill([
            'bus_id' => $this->record->bus_id,
            'program_km' => $this->record->program_km ?? 0,
            'start_place_id' => $this->record->start_place_id,
            'end_place_id' => $this->record->end_place_id,
            'transport_notes' => $this->record->transport_notes ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dane transportowe')
                    ->description('Zarządzaj transportem dla tego szablonu imprezy')
                    ->schema([
                        Select::make('bus_id')
                            ->label('Autobus')
                            ->options(Bus::all()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->placeholder('Wybierz autobus'),

                        TextInput::make('program_km')
                            ->label('Program (km)')
                            ->numeric()
                            ->default(0)
                            ->placeholder('Ilość kilometrów w realizacji programu'),

                        Select::make('start_place_id')
                            ->label('Miejsce początkowe')
                            ->options(fn() => \App\Models\Place::orderBy('name')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->nullable()
                            ->placeholder('Wybierz miejsce początkowe'),

                        Select::make('end_place_id')
                            ->label('Miejsce końcowe')
                            ->options(fn() => \App\Models\Place::orderBy('name')->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->nullable()
                            ->placeholder('Wybierz miejsce końcowe'),

                        Textarea::make('transport_notes')
                            ->label('Notatki transportowe')
                            ->placeholder('Dodatkowe informacje o transporcie...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $this->record->update([
            'bus_id' => $data['bus_id'],
            'program_km' => $data['program_km'] ?? 0,
            'start_place_id' => $data['start_place_id'],
            'end_place_id' => $data['end_place_id'],
            'transport_notes' => $data['transport_notes'],
        ]);

        \Filament\Notifications\Notification::make()
            ->title('Transport zapisany!')
            ->body('Dane transportowe zostały pomyślnie zaktualizowane.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Wróć do edycji')
                ->icon('heroicon-o-arrow-left')
                ->url(fn() => static::getResource()::getUrl('edit', ['record' => $this->record->id]))
                ->color('gray'),
        ];
    }
}
