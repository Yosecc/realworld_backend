<?php

namespace App\Filament\Pages;

use App\Models\User;

use App\Models\State;
use App\Models\Gender;
use App\Models\Reason;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Frequency;

use App\Models\Experience;
use Filament\Actions\Action;
use App\Models\ExercisePlace;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;

use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;

class MyProfile extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.my-profile';

    public ?array $data = [];
    public User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->form->fill($this->user->toArray());
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->requiresConfirmation()
                ->action(function () {
                    $this->update();
                }),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal information')
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                    ])
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('last_name')->required(),
                        TextInput::make('email')->readOnly()->required(),
                        // TextInput::make('password')->required(),
                        Select::make('gender_id')->options(Gender::get()->pluck('description','id')->toArray())->required(),
                        DatePicker::make('date_of_birth')->required(),
                    ]),
                Section::make('Contact Information')
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                    ])
                    ->schema([
                        Select::make('country_id')->options(Country::get()->pluck('description','id')->toArray())->searchable(),
                        Select::make('state_id')->options(State::get()->pluck('name','id')->toArray())->searchable(),
                        TextInput::make('address'),
                        TextInput::make('city'),
                        TextInput::make('postal_code'),
                        TextInput::make('telephone')->numeric(),
                    ]),
                Section::make('Fitness Information')
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                    ])
                    ->schema([
                        Select::make('experience_id')->options(Experience::get()->pluck('description','id')->toArray()),
                        TextInput::make('size'),
                    ]),
                Section::make("What's your main reason for joining")
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                    ])
                    ->schema([
                        Select::make('reason_id')->options(Reason::get()->pluck('description','id')->toArray()),
                        Select::make('exercise_place_id')->options(ExercisePlace::get()->pluck('description','id')->toArray()),
                    ]),
                Section::make("How often do you want to work out?")
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                    ])
                    ->schema([
                        Select::make('frequency_id')->options(Frequency::get()->pluck('description','id')->toArray()),

                    ])
            ])->statePath('data')->model($this->user)->columns(2);
    }

    public function update(): void
    {
        $this->user->update($this->form->getState());
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
        // User::where('id', $this->user->id)->update($this->form->getState());
        
    }
}
