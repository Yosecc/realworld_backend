<?php

namespace App\Filament\Resources\ProgramResource\Pages;

use App\Filament\Resources\ProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProgram extends ViewRecord
{
    protected static string $resource = ProgramResource::class;

    // protected static ?string $title = 'Custom Page Title';
    public function getTitle(): string
    {
        return $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        
        return [
            // Actions\EditAction::make(),
        ];
    }
}
