<?php

namespace App\Livewire;

use App\Models\Program;

use Livewire\Component;
use Filament\Tables\Table;
use Forms\Components\TextInput;
use App\Models\SubscriptionProgram;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class MyProgramsList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

   
    
    public function table(Table $table): Table
    {

        return $table
            ->query(SubscriptionProgram::query()->where("user_id",Auth::user()->id)->where('status_id',1)->where('is_active',1))
            ->columns([
                Stack::make([
                    ImageColumn::make('program.image')
                    ->state(function (SubscriptionProgram $record) {
                        return Storage::disk('remoto')->url($record->program->image);
                    })
                    ->width('100%')
                    ->height(200),
                    TextColumn::make('program.name')->searchable()->extraAttributes(['style' => 'margin-top: 0.5rem;']),
                ]),
                
            ])
            ->paginated([4, 8, 12, 'all'])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                // TextInputColumn::make('name')

            ])
            ->actions([
                Action::make('view')
                ->url(fn (SubscriptionProgram $record): string => 'programs/'.$record->program->id )
                ->openUrlInNewTab()
            ])
            ->bulkActions([
                // ...
            ])
            ;
    }

    public function render()
    {
        return view('livewire.my-programs-list');
    }
}
