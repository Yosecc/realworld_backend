<?php

namespace App\Filament\Pages;

use Livewire\Component;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact as EmailContact;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class Contact extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.contact';

    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send')
                ->requiresConfirmation()
                ->action(function () {
                    $this->create();
                }),
        ];
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->default(Auth::user()->name)
                    ->required(),
                MarkdownEditor::make('content')
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                    ]),
                // ...
            ])
            ->statePath('data');
    }
    
    public function create(): void
    {
        // dd(config('app.mail_contact'));
        Mail::to(config('app.mail_contact'))->send(new EmailContact(
            $this->form->getState()['name'],
            $this->form->getState()['content'],
        ));

        Notification::make()
            ->title('Message sent')
            ->success()
            ->send();
    }
}
