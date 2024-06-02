<?php

namespace App\Livewire;


use App\Models\Program;
use Livewire\Component;
use Filament\Tables\Table;
use Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
 

class ProgramsList extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;
    

    public function table(Table $table): Table
    {
        return $table
            ->query(Program::query()->where('status_id',1))
            ->columns([
                Stack::make([
                    ImageColumn::make('image')
                    ->state(function (Program $record) {
                        return Storage::disk('remoto')->url($record->image);
                    })
                    ->width('100%')
                    ->height(200),
                    TextColumn::make('name')->searchable()->extraAttributes(['style' => 'margin-top: 0.5rem;']),
                ]),
                
            ])
            ->paginated([12, 24, 50, 100, 'all'])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                // TextInputColumn::make('name')

            ])
            ->actions([
                Action::make('view')
                ->url(fn (Program $record): string => 'programs/'.$record->id )
                // ->openUrlInNewTab()
            ])
            ->bulkActions([
                // ...
            ]);
    }


    public function render()
    {
        return view('livewire.programs-list');
    }
}
