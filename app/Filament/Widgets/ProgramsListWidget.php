<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ProgramsListWidget extends Widget
{
    protected static string $view = 'filament.widgets.programs-list-widget';

    protected int | string | array $columnSpan = 'full';
}
