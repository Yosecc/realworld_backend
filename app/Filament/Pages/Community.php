<?php

namespace App\Filament\Pages;

use Livewire\Component;

use Filament\Pages\Page;
use App\Models\BlogPosts;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class Community extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public ?array $posts;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static string $view = 'filament.pages.community';



    public function table(Table $table): Table
    {
        
        return $table
            ->query(BlogPosts::query()->orderBy('created_at','desc'))
            ->paginated([4, 8, 12, 'all'])
            ->columns([
                Stack::make([
                    Grid::make([
                        'xl' => 2,
                    ])
                    ->schema([
                        Stack::make([
                            TextColumn::make('title')->searchable(),
                            ImageColumn::make('image')
                                ->state(function (BlogPosts $record) {
                                    return Storage::disk('remoto')->url($record->banner);
                                })->label('')->height(300),
                            TextColumn::make('created_at')->searchable()->since(),      
                        ]),
                        TextColumn::make('content')->searchable()->html(),
                    ])
                ]),
            ])
            ->paginated([1, 2, 4, 'all'])
            ->defaultPaginationPageOption(1);
    }
}
