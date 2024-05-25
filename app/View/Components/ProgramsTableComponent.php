<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProgramsTableComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    public function table(Table $table): Table
{
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('slug'),
                IconColumn::make('is_featured')
                    ->boolean(),
            ]);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $table = $this->table();

        return view('components.programs-table-component', ['table' => $table]);
    }
}
